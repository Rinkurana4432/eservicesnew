<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    // Serve PDF
    public function pdf($filename = null)
    {
        if (!$filename) {
            show_404();
        }

        // Sanitize the filename
        $filename = basename($filename);

        // Path to assets folder (still protected by controller)
        $filepath = FCPATH . 'assets/pdf/' . $filename;

        if (!file_exists($filepath)) {
            show_404();
        }

        // Optional: restrict access to logged-in users
        // if (!$this->session->userdata('logged_in')) {
        //     show_error('Not authorized', 403);
        // }

        // Force download / inline view
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        readfile($filepath);
        exit;
    }
}
