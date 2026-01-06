<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {
	 public function __construct() {
        parent::__construct();
        // if (!is_login()) {
        //     redirect( base_url().'auth/login', 'refresh');
        // }
        $this->load->helper(['url', 'function']);
        $this->load->library('session');
        $this->load->database();
        $this->db2 = $this->load->database('second', TRUE);
        $this->load->library('session');


        $this->data['css_files'] = [
            base_url('assets/css/bootstrap.min.css'),
            base_url('assets/css/style.css')
        ];

        $this->data['js_files'] = [
            base_url('assets/js/bootstrap.bundle.min.js'),
            base_url('assets/js/custom.js')
        ];

        $this->data['copyright'] = '© 2026 Haryana Government. All Rights Reserved.';


    }    
   
   public function index(){
   	//$this->data['department_name'] = 'Forest Department';
   	$this->data['pageTitle']= 'Forest Department - Login';
	$this->data['breadcrumbs'] = array('Login');
    $this->data['constantx'] = AUTHORIZATION_ENDPOINT;
		$this->load->view('layout/header', $this->data);
		$this->load->view('landingView',$this->data);
		$this->load->view('layout/footer', $this->data);
	}

    public function cscconnect() {
        session_start();
        $state = rand(10000, 99999);
        $_SESSION['connect_state'] = $state;
        $redirectUri = CLIENT_CALLBACK_URI; //echo $redirectUri;die;
        $auth_parameters =
            "response_type=code&client_id=" . CLIENT_ID .
            "&redirect_uri=" . $redirectUri . "&state=" . $state;
        $url = AUTHORIZATION_ENDPOINT . "?" . $auth_parameters;
        header("Location:" . $url); //echo $url;die;
    }

public function connect_success() {
        // if code & state are not there in GET request
        if (!isset($_GET['code']) || !isset($_GET['state'])) {
            if (isset($_SESSION['User'])) {
                unset($_SESSION['User']);
            }
            $this->session->set_flashdata('msg', 'Logout Successfully.');
            redirect('landing/logout');
        }
        $code = $_GET['code'];
        $state = $_GET['state'];
        session_start();

        // if connect_state is not available in session
        if (!isset($_SESSION['connect_state'])) {
            if (isset($_SESSION['User'])) {
                unset($_SESSION['User']);
            }
            $this->session->set_flashdata('msg', 'Invalid request! STATE unavailable.');
            redirect('landing/logout');
            
        }

        if (!$state || $state != $_SESSION['connect_state']) {
            exit('STATE mismatch');
        }
        unset($_SESSION['connect_state']);

        if (!$code) {
            exit('No code!!');
        }

        //fetch token
        $post_data = array(
            'code' => $code,
            'redirect_uri' => REDIRECT_URI,
            'grant_type' => 'authorization_code',
            'client_id' => CLIENT_ID,
            'client_secret' => $this->cscencrypt(CLIENT_SECRET),
        );
        $token_resp = $this->cscfetch_data(TOKEN_ENDPOINT, $post_data, false);
        $token_resp_data = (array) json_decode($token_resp);
        $access_token = $token_resp_data && isset($token_resp_data['access_token']) ? $token_resp_data['access_token'] : false;

        if (!$access_token) {
            // exit('No token');
            unset($_SESSION['User']);
            $this->session->set_flashdata('msg', 'Token not found!');
        
            redirect('landing/logout');
        }

        $header_data = array(
            'Authorization' => 'Bearer ' . $access_token,
        );

        $response = $this->cscfetch_data(RESOURCE_URL . '?access_token=' . $access_token, false, $header_data);
        $resp_json = (array) json_decode($response, true);
        $_SESSION['User'] = ($resp_json && isset($resp_json['User'])) ? $resp_json['User'] : false;

        
        // if userDate is present in session, log-in user as VLE user
        if (isset($_SESSION['User']) && !empty($_SESSION['User'])) {
            $loginType = base64_encode('cscconnect');
            $encEmail = base64_encode($_SESSION['User']['email']);
            $this->redirect(array('auth', 'type' => $loginType, 'em' => $encEmail));
        } else {
            unset($_SESSION['User']);
            $this->session->set_flashdata('msg', 'Unauthorized login!');
            redirect('landing/logout');
        }

    }




    public function cscfetch_data($url, $post, $heads) {
        $curl = curl_init();
        $curl_opts = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLINFO_HEADER_OUT => false,
            CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
            CURLOPT_POST => 1,
            // CURLOPT_POSTFIELDS => $post
        );

        if ($post && is_array($post) && count($post) > 0) {
            $curl_opts[CURLOPT_POSTFIELDS] = $post;
        }

        if ($heads && is_array($heads) && count($heads) > 0) {
            $curl_opts[CURLOPT_HTTPHEADER] = $heads;
        }

        curl_setopt_array($curl, $curl_opts);

        $result = curl_exec($curl);
        if (!$result) {
            $httpcode = curl_getinfo($curl);
            $curlFailure = array('Error code' => $httpcode, 'URL' => $url, 'post' => $post, 'LOG' => "");
            return json_encode($curlFailure);
            exit;
            // echo '<pre>';
            // print_r(array('Error code' => $httpcode, 'URL' => $url, 'post' => $post, 'LOG' => ""));
            // exit("Error: 378972");
        }
        curl_close($curl);
        return $result;
    }



 public function selfloginview(){
        $this->data['pageTitle']= 'Forest Department - Login';
    $this->data['breadcrumbs'] = array('Login');
    $this->data['constantx'] = AUTHORIZATION_ENDPOINT;
        $this->load->view('layout/header', $this->data);
        $this->load->view('self_login',$this->data);
        $this->load->view('layout/footer', $this->data);

 }


 public function selflogin(){
        // Allow only POST
        if ($this->input->server('REQUEST_METHOD') === 'GET') {
            show_error('Access denied', 401);
        }

        if ($this->input->post()) {

            $post = $this->security->xss_clean($this->input->post());

            /**
             * ===============================
             * LOGIN USING UID
             * ===============================
             */
            if (!empty($post['uid'])) {

                $uid = trim($post['uid']);

                $row = $this->db2
                    ->where('Resident_UID', $uid)
                    ->get('tblResident')
                    ->row_array();

                if (!$row) {
                    $this->session->set_flashdata('error', 'No user with provided UID exists!');
                    redirect('landing/selfloginview');
                }

                if (empty($row['Mobile_Number'])) {
                    $this->session->set_flashdata('error', 'Mobile number not found for this UID.');
                    redirect('landing/selfloginview');
                }

                $mobileNo = $row['Mobile_Number'];
                $otp =  generate_otp(4);

                $this->db->insert('otp_info', ['otp_code' => $otp,'mobile_number' => $mobileNo,'created_timestamp' => time()]);

                sendOtpSms($mobileNo,"Your OTP for Forest Dept login is {$otp}. Do not share.");

                $this->load->view('validateotp', [
                    'mobile' => $mobileNo
                ]);
                return;
            }

            /**
             * ===============================
             * LOGIN USING MOBILE + YEAR 
             * ===============================
             */
            if (!empty($post['mobile']) && !empty($post['year'])) {

                $mobileNo = trim($post['mobile']);
                $year = trim($post['year']);

                $count = $this->db2
                    ->where('Mobile_Number', $mobileNo)
                    ->where('YEAR(Date_of_Birth)', $year, FALSE)
                    ->where('refEvent_Init', 1)
                    ->count_all_results('tblResident');

                if ($count !== 1) {
                    $this->session->set_flashdata('error', 'Invalid credentials!');
                    redirect('landing/selfloginview');
                }

                $otp =generate_otp(4);

                $this->db->insert('otp_info', ['otp_code' => $otp,'mobile_number' => $mobileNo,'created_timestamp' => time()]);

                sendOtpSms($mobileNo,"Your OTP for Forest Dept login is {$otp}. Do not share.");

                $this->load->view('validateotp', ['mobile' => $mobileNo]);
                return;
            }

            $this->session->set_flashdata('error', 'Invalid request!');
            redirect('landing/selfloginview');
        }

        // load page
        $this->load->view('selfloginview');
    }


    public function logout(){
    
        $this->session->sess_destroy();
//        $this->session->unset_userdata('fd_data');
  //      $this->session->unset_userdata('querty');
        //delete_cookie("tokenCookie");
            session_unset();
         session_destroy();
        $this->session->set_flashdata('msg', 'Logout Successfully.');
        redirect('landing');
    }




}