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
         $this->db =$this->load->database();
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

        //$this->data['copyright'] = '© 2026 Haryana Government. All Rights Reserved.';


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
        $this->load->database();
        if ($this->input->server('REQUEST_METHOD') === 'GET') {
            show_error('Access denied', 401);
        }
        if ($this->input->method(TRUE) === 'POST') {
            $post = $this->security->xss_clean($this->input->post());

            /**
             * ---------------------------
             * LOGIN USING UID
             * ---------------------------
             */
            if (!empty($post['uid'])) {
                $uid = $post['uid'];
                $query = $this->db2->where('Resident_UID', $uid)->get('tblResident');
                $row = $query->row_array();
                if (!empty($row)) {
                    if (!empty($row['Mobile_Number'])) {
                        $mobileNo = $row['Mobile_Number'];
                        $HMAC_KEY = privateKey;
                        $HMAC = hash_hmac('sha1', $HMAC_KEY . $mobileNo, $HMAC_KEY);
                        $otp =  generate_otp(4);
                        $messageStr = 'You requested OTP for Forest Dept. login. Your OTP is ' . $otp . '. Please DO NOT SHARE it with anyone.';
                        try {
                            sendOtpSms($mobileNo,$messageStr);

                            // insert OTP
                           
                            $createdTmStmp = time();
                            $data2 = [
                                    'otp_code' => $otp,
                                    'mobile_number' => $mobileNo,
                                    'created_timestamp' => $createdTmStmp
                                ];

                                $this->db->insert('otp_info', $data2);

                            $data['post'] = ['mobile' => $mobileNo,'HMAC'   => $HMAC];
                             $this->load->view('layout/header', $this->data);
                             $this->load->view('validateotp', $data);
                             $this->load->view('layout/footer', $this->data);
                                return;
                        } catch (Exception $e) {
                            $this->session->set_flashdata('error', 'Something went wrong! Unable to send SMS. Please try again.');
                        }
                    } else {
                        $this->session->set_flashdata('error', "Mobile no. corresponding to provided UID doesn't exist!");
                    }
                } else {
                    $this->session->set_flashdata('error', 'No user with provided UID exists! Please choose another option to login.');
                }
            }

            /**
             * ---------------------------
             * LOGIN USING MOBILE + YEAR
             * ---------------------------
             */
            elseif (!empty($post['mobile']) && !empty($post['year'])) {
                $mobileNo     = trim($post['mobile']);
                $yearOfBirth  = trim($post['year']);
                $sql = "SELECT COUNT(*) AS count FROM tblResident WHERE Mobile_Number = ? AND YEAR(Date_of_Birth) = ? AND refEvent_Init = 1 LIMIT 1";
                $query = $this->db2->query($sql, [$mobileNo, $yearOfBirth]);
                $row   = $query->row_array();
                if ($row['count'] == 1) {
                    $HMAC_KEY = privateKey;
                    $HMAC = hash_hmac('sha1', $HMAC_KEY . $mobileNo, $HMAC_KEY);
                    $otp =generate_otp(4);
                    $messageStr = 'You requested an OTP for Forest Dept. login. Your OTP is ' . $otp . '. Please DO NOT SHARE it with anyone.';
                    try {
                      sendOtpSms($mobileNo,$messageStr);
                       $createdTmStmp = time();

                       //echo $createdTmStmp;die();
                            $data2 = [
                                    'otp_code' => $otp,
                                    'mobile_number' => $mobileNo,
                                   'created_timestamp' => $createdTmStmp
                                ];

                        $this->db->insert('otp_info', $data2);
                        //echo $this->db->last_query();die();

                        $data['post'] = [
                            'mobile' => $mobileNo,
                            'HMAC'   => $HMAC
                        ];
                        $this->load->view('layout/header', $this->data);
                        $this->load->view('validateotp', $data);
                        $this->load->view('layout/footer', $this->data);
                        return;

                    } catch (Exception $e) {
                        $this->session->set_flashdata('error', 'Something went wrong! Unable to send SMS. Please try again.');
                    }

                } else {
                    $this->session->set_flashdata('error', 'Invalid credentials!');
                }
            }
        }

        // default view
        $this->load->view('auth/selflogin');
    }//



    public function validateotp(){
        if ($this->input->method(TRUE) === 'POST') {
            $post = $this->security->xss_clean($this->input->post());
            
            if (!empty($post['otp'])) {
                $mobField = base64_encode('mo');
                $mobileNo = base64_decode($post[$mobField]);

                // generate HMAC
                $HMAC_KEY = privateKey;
                $HMAC = hash_hmac('sha1', $HMAC_KEY . $mobileNo, $HMAC_KEY);

                if ($post['HMAC'] == $HMAC) {

                    $otp = $post['otp'];

                    // check OTP
                    $this->load->database();
                    $sql = "SELECT COUNT(*) AS count FROM otp_info WHERE otp_code = ? AND mobile_number = ? ORDER BY created_timestamp DESC LIMIT 1";

                    $query = $this->db->query($sql, [$otp, $mobileNo]);
                    $row   = $query->row_array();

                   

                    if ($row['count'] == 1) {

                        // fetch resident
                        $resQuery = $this->db2->where('Mobile_Number', $mobileNo)->limit(1)->get('tblResident');

                        $resRow = $resQuery->row_array();
                        $residentId = $resRow['Resident_ID'];

                        // session set
                        $this->session->set_userdata(['residentId' => $residentId,'userType'   => 'self']);

                        
                        $loginAttribs = [
                            'username'   => 'demo',
                            'password'   => 'demo',
                            'rememberMe'=> '0'
                        ];

                        //$this->load->model('Login_model');
                       // if ($this->Login_model->login($loginAttribs)) {
                        if ($row['count'] == 1) {

                            $loginTime = time();
                            initLogin($loginTime);

                            redirect(base_url('user/services'), 'refresh');

                        } else {
                           logoutUser();
                            $this->session->set_flashdata(
                                'error',
                                'Access Denied (You are not the Authorized Person)'
                            );
                        }

                    } else {
                        $data['error'] = 'OTP mismatch! Please try again.';
                        $this->load->view('validateotp', $data);
                        return;
                    }

                } else {
                    $data['error'] = 'Mobile number has been tampered! Please try again.';
                    $this->load->view('validateotp', $data);
                    return;
                }
            }
        }

        $this->load->view('validateotp');
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

