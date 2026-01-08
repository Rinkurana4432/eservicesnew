<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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