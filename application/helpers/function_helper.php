<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    function is_login(){
        $CI =& get_instance();
        return $CI->session->userdata('is_login') === TRUE;
    }

    function generate_otp($length = 6){
        if (!is_numeric($length) || $length < 1) {
            $length = 6;
        }

        $length = (int) round($length);

        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;

        return rand($min, $max);
    }


    function sendOtpSms($mobile, $message){
          if (isset($mobile) && !empty($mobile) && $mobile != 'null'){
            $fields = '';
            $messageStr = $message;         
        $key = hash('sha512',trim(USERNAME).trim(SENDERID).trim($messageStr).trim(DEPTSECUREKEY));
            $data = array(
                "username" => trim(USERNAME),
                "password" => trim(PASSWORD),
                "senderid" => trim(SENDERID),
             //   "smsservicetype" => "singlemsg", //*Note* for single sms enter ”singlemsg” , for bulk
         "smsservicetype" => "otpmsg",
         "templateid" => trim(TEMPLATEID),
        "key" => trim($key),
        // "deptSecureKey" => trim(DEPTSECUREKEY),
               // "mobileno" => $mobile,
               // "content"=>$messageStr
            );
           $data['mobileno'] = trim($mobile);
            $data['content'] = trim($messageStr);
       
         //   ob_clean();
            foreach ($data as $key => $value) {
                $fields .= $key . '=' . $value . '&';
            }
            $fields = rtrim($fields, '&');
            
           // $url = "https://msdgweb.mgov.gov.in/esms/sendsmsrequest";
            $url = "https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT";
            $post = curl_init();       
            curl_setopt($post, CURLOPT_SSLVERSION, 6); // use for systems supporting TLSv1.2 or comment the line
            curl_setopt($post,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($post, CURLOPT_URL, $url);
            curl_setopt($post, CURLOPT_POST, count($data));
            curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($post); 
            $httode = explode(",", $result);
            $httpCode = $httode[0];
        //echo "<pre>";print_r($result );die; 
             curl_close($post);
            if ($httpCode == 401) {
                return "Credentials Error, invalid username or password";
            } else if ($httpCode == 402) {
                return "Messages submitted successfully";
            } else if ($httpCode == 403) {
                return "Credits not available";
            } else if ($httpCode == 404) {
                return "Internal Database Error";
            } else if ($httpCode == 405) {
                return "Internal Networking Error";
            } else if ($httpCode == 406) {
                return "Invalid or Duplicate numbers";
            } else if ($httpCode == 407 || $httpCode == 408) {
                return "Network Error on SMSC";
            } else if ($httpCode == 409) {
                return "SMSC response timed out, message will be submitted";
            } else if ($httpCode == 410) {
                return "Internal Limit Exceeded, Contact support";
            } else if ($httpCode == 411 || $httpCode == 412) {
                return "Sender ID not approved.";
            } else if ($httpCode == 413) {
                return "Suspect Spam, we do not accept these messages";
            } else if ($httpCode == 414) {
                return "Rejected by various reasons by the operator such as DND, SPAM";
            }
        }
    }

 function activedistrictsbystateid($state_id){
        // Get CI instance
        $CI =& get_instance();

        // Load second database
        $db2 = $CI->load->database('second', TRUE);

        $db2->select('tlr.LR_Name, tlr.LR_ID, dist.isFellingApplicable');
        $db2->from('tblLandRegion AS tlr');
        $db2->join('tblDistrict AS dist', 'dist.District_ID = tlr.LR_ID', 'left');
        $db2->where('dist.State_ID', $state_id);

        $query = $db2->get();

        return $query->result_array();
    }


  function engtohin($text) {
    $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=hi&dt=t&q=' . urlencode($text);
    $response = file_get_contents($url);
    $result = json_decode($response, true);
    return $result[0][0][0] ?? $text;
}


function initLogin($time = null){
        $CI =& get_instance(); // Get CI instance
        $CI->load->library('session');

        // Get resident ID from session
        $RID = $CI->session->userdata('residentId');

        // Get user agent and IP
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

        // Generate hash
        $hash = $ua . "_" . $ip . "_" . $RID;
        $hash = hash_hmac("sha1", $hash, "orphan");

        // Set secure cookie
        $cookie = array(
            'name'   => 'iviss_cookie',
            'value'  => $hash,
            'expire' => 0, // session cookie
            'secure' => true,  // send over HTTPS only
            'httponly' => true,
        );
        set_cookie($cookie);

        // Call user visit log function (you need to convert this too in CI if it's Yii specific)
        if (function_exists('userVisitLogs')) {
            userVisitLogs($time, 'login');
        }
    }

    function logoutUser(){
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->load->helper('cookie'); // For deleting cookies
        $CI->load->database();

        // Log the logout time
        $logoutTime = time();
        userVisitLogs($logoutTime, 'logout'); // call the CI version of userVisitLogs

        // Delete cookies
        delete_cookie('iviss_cookie');
        delete_cookie('PHPSESSID');

        // Destroy session
        $CI->session->sess_destroy();

        // Set flash message
        $CI->session->set_flashdata('error', "Invalid refresh token!");
    }

    function userVisitLogs($time, $activity){
        if (isset($time) && isset($activity)) {

            $CI =& get_instance(); // get CI instance
            $CI->load->library('session');
            $CI->load->database(); // load default database

            $residentId = '';
            $userType = '';

            // Resident login (default or self)
            if ($CI->session->userdata('residentId')) {
                $userType = 'Operator';
                if ($CI->session->userdata('userType') == 'self') {
                    $userType = 'Self Login';
                }
                $residentId = $CI->session->userdata('residentId');

            } 
            // Invest Haryana login
            elseif ($CI->session->userdata('investProjectId')) {
                $residentId = $CI->session->userdata('investProjectId');
                $userType = 'Invest Haryana';
            } 
            // CSC login
            elseif ($CI->session->userdata('User')['username']) {
                $userType = 'CSC User';
                $cscId = $CI->session->userdata('User')['csc_id'];
                $userName = $CI->session->userdata('User')['username'];

                // Fetch CSC resident ID
                $cscRow = $CI->db
                    ->where('name', $userName)
                    ->where('csc_id', $cscId)
                    ->limit(1)
                    ->get('forest_newcsc_info')
                    ->row_array();

                if (!empty($cscRow)) {
                    $residentId = $cscRow['csc_rid'];
                }
            }

            $systemIp = $_SERVER['REMOTE_ADDR'];

            // Insert log
            $logData = array(
                'resident_id'    => $residentId,
                'idm_token'      => $systemIp,
                'event'          => $activity,
                'originating_url'=> $userType,
                'time_stamp'     => $time
            );

            $CI->db->insert('idm_logs', $logData);
        }
    }