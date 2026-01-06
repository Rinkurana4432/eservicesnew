<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {
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
    	$this->data['pageTitle']= 'Forest Department - Self Registration';
		$this->data['breadcrumbs'] = array('Login');
    
		$this->load->view('layout/header', $this->data);
		$this->load->view('registration/registration_view',$this->data);
		$this->load->view('layout/footer', $this->data);
    }

    public function step2(){
    // Allow only POST request
    if ($this->input->method() !== 'post') {
        redirect('registration');
        return;
    }

    // Check required POST value
    if (!$this->input->post('otp_mobile_number')) {
        redirect('registration');
        return;
    }

    // Sanitize POST data
    $postData = $this->input->post(NULL, TRUE);

    $mobile = base64_decode($postData['otp_mobile_number']);

    // Generate HMAC
    $HMAC_KEY  = privateKey; // defined in constants.php
    $hmac_data = $HMAC_KEY . $mobile;
    $HMAC      = hash_hmac('sha1', $hmac_data, $HMAC_KEY);

    // Validate HMAC
    if ($HMAC === $postData['HMAC_TOKEN']) {

        // Load step2 view
        $this->load->view('step2', [
            'post' => $postData
        ]);

    } else {
        // Invalid request
        show_error('Invalid request!', 401);
    }
}

		public function sendotp() {

			echo 'there';die();
		}

  

  }     