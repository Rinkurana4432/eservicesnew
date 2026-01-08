<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller {
	 public function __construct() {
        parent::__construct();
       
        $this->load->helper(['url', 'function']);
        $this->load->library('session');
         $this->db =$this->load->database();
        $this->db2 = $this->load->database('second', TRUE);
        $this->load->library('session');

 		if (!$this->session->userdata('residentId')) {
			    redirect('landing');
			}

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
    	if ($this->session->userdata('residentId')) {
			    echo "Session is maintained. Resident ID: " . $this->session->userdata('residentId');
			} else {
			    echo "Session NOT maintained";
			}



    	die();
    }



}     