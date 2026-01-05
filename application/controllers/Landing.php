<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {
	 public function __construct() {
        parent::__construct();
        // if (!is_login()) {
        //     redirect( base_url().'auth/login', 'refresh');
        // }
        $this->load->helper('url');

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




}