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


    public function gisindex(){
        $this->data['pageTitle']   = 'GIS Based Applications Summary';
        $this->data['breadcrumbs'] = array('Dashboard');
        $this->data['gis'] = $this->Dashboard_model->get_gis_summary();
        $this->data['total']    = $this->data['gis']['total']    ?? 0;
        $this->data['pending']  = $this->data['gis']['pending']  ?? 0;
        $this->data['approved'] = $this->data['gis']['approved'] ?? 0;
        $this->data['rejected'] = $this->data['gis']['rejected'] ?? 0;

         $this->data['totalBlockApproved'] =
        $this->data['pending'] + $this->data['approved'];

        $this->load->view('layout/header', $this->data);
        $this->load->view('dashboard/gisview', $this->data);
        $this->load->view('layout/footer');
    }

    public function roinspectionReport(){
        $this->data['pageTitle'] = 'RO Inspection Reports';
        $this->data['breadcrumbs'] = array('Dashboard');
        $fromdate = $this->input->get('from_date', TRUE);
        $todate   = $this->input->get('to_date', TRUE);

        $this->data['fromdate'] = $fromdate;
        $this->data['todate']   = $todate;
       $this->data['records']  = [];

        if (!empty($fromdate) && !empty($todate)) {
            $this->data['records'] = $this->Dashboard_model->roinspectionreport($fromdate, $todate);
        }

        $this->load->view('layout/header', $this->data);
        $this->load->view('dashboard/roinspection_report', $this->data);
        $this->load->view('layout/footer');

    }


}
