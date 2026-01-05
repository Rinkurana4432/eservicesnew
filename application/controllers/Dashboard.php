<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
          $this->load->database(); 
        $this->load->model('Dashboard_model');
        $this->load->helper(['url', 'form']);


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
        $this->data['pageTitle']= 'Forest Department - Dashboard';
        $this->data['breadcrumbs'] = array('Dashboard');
        $data = [];
        $fromdate = '';
        $todate   = '';
        $result   = [];



        if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {

            if (is_array($_POST['from_date']) || is_array($_POST['to_date'])) {
                show_error('Invalid request', 400);
            }

            $fromdate = substr(preg_replace('/[^0-9\-]/', '', $_POST['from_date']), 0, 10);
            $todate   = substr(preg_replace('/[^0-9\-]/', '', $_POST['to_date']), 0, 10);

            $result = $this->Dashboard_model->dashboarddetail($fromdate, $todate);

            
        }

        $this->data['from_date'] = $fromdate;
        $this->data['to_date']   = $todate;
        $this->data['result']    = $result;

        $this->load->view('layout/header', $this->data);
        $this->load->view('dashboard/index', $this->data);
        $this->load->view('layout/footer', $this->data);
    }
}
