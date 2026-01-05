<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function dashboarddetail($fromdate, $todate)
    {
        // Format dates safely
        $from_date = date('Y-m-d', strtotime($fromdate));
        $to_date   = date('Y-m-d', strtotime($todate));

        // Status codes
        $status_app   = 1;
        $sts_pending  = -1;
        $sts_ro       = 4;
        $sts_ro1      = 5;
        $sts_cf       = 6;
        $sts_cf1      = 7;
        $sts_rejected = 2;

        $total = [];

        /* ================= TREE FELLING ================= */

        // Total received
        $sql = "SELECT DISTINCT srn
                FROM forest_main
                WHERE status != 8
                AND DATE(req_date) BETWEEN ? AND ?";
        $count_total = $this->db->query($sql, [$from_date, $to_date])->num_rows();

        // Approved
        $sql = "SELECT fm.srn
                FROM forest_main fm
                LEFT JOIN request_response fr ON fr.response_srn = fm.srn
                WHERE fm.status = ?
                AND DATE(fm.req_date) BETWEEN ? AND ?";
        $count_total_app = $this->db->query($sql, [
            $status_app, $from_date, $to_date
        ])->num_rows();

        // Pending
        $sql = "SELECT srn
                FROM forest_main
                WHERE status IN (?,?,?,?,?)
                AND DATE(req_date) BETWEEN ? AND ?";
        $count_total_pen = $this->db->query($sql, [
            $sts_pending, $sts_ro, $sts_ro1, $sts_cf, $sts_cf1,
            $from_date, $to_date
        ])->num_rows();

        // Approved beyond RTS
        $sql = "SELECT fm.srn
                FROM forest_main fm
                LEFT JOIN request_response fr ON fr.response_srn = fm.srn
                WHERE fm.status = ?
                AND DATE(fr.response_time) > DATE(fm.rtsdate)
                AND DATE(fm.req_date) BETWEEN ? AND ?";
        $count_app_beyond = $this->db->query($sql, [
            $status_app, $from_date, $to_date
        ])->num_rows();

        $count_app_intime = $count_total_app - $count_app_beyond;

        // Pending beyond RTS
        $sql = "SELECT fm.srn
                FROM forest_main fm
                WHERE fm.status IN (?,?,?,?,?)
                AND CURDATE() > DATE(fm.rtsdate)
                AND DATE(fm.req_date) BETWEEN ? AND ?";
        $count_pen_beyond = $this->db->query($sql, [
            $sts_pending, $sts_ro, $sts_ro1, $sts_cf, $sts_cf1,
            $from_date, $to_date
        ])->num_rows();

        $count_pen_intime = $count_total_pen - $count_pen_beyond;

        // Rejected
        $sql = "SELECT srn
                FROM forest_main
                WHERE status = ?
                AND DATE(req_date) BETWEEN ? AND ?";
        $count_total_rej = $this->db->query($sql, [
            $sts_rejected, $from_date, $to_date
        ])->num_rows();

        $sql = "SELECT fm.srn
                FROM forest_main fm
                LEFT JOIN request_response fr ON fr.response_srn = fm.srn
                WHERE fm.status = ?
                AND DATE(fr.response_time) > DATE(fm.rtsdate)
                AND DATE(fm.req_date) BETWEEN ? AND ?";
        $count_rej_beyond = $this->db->query($sql, [
            $sts_rejected, $from_date, $to_date
        ])->num_rows();

        $count_rej_intime = $count_total_rej - $count_rej_beyond;

        $total['tree_felling'] = [
            'service'         => 'Permissions for felling of trees',
            'total_rec'       => $count_total,
            'total_pending'   => $count_total_pen,
            'beyond_time_pen' => $count_pen_beyond,
            'within_time_pen' => $count_pen_intime,
            'total_approved'  => $count_total_app,
            'beyond_time_app' => $count_app_beyond,
            'within_time_app' => $count_app_intime,
            'total_rejected'  => $count_total_rej,
            'beyond_time_rej' => $count_rej_beyond,
            'within_time_rej' => $count_rej_intime
        ];

        /* ================= CLARIFICATION ================= */

        $sql = "SELECT DISTINCT srn
                FROM forest_clarification
                WHERE status != 8
                AND DATE(req_date) BETWEEN ? AND ?";
        $count_total_clar = $this->db->query($sql, [$from_date, $to_date])->num_rows();

        $sql = "SELECT srn
                FROM forest_clarification
                WHERE status = ?
                AND DATE(req_date) BETWEEN ? AND ?";
        $count_clar_app = $this->db->query($sql, [
            $status_app, $from_date, $to_date
        ])->num_rows();

        $sql = "SELECT srn
                FROM forest_clarification
                WHERE status IN (?,?,?,?,?)
                AND DATE(req_date) BETWEEN ? AND ?";
        $count_clar_pen = $this->db->query($sql, [
            $sts_pending, $sts_ro, $sts_ro1, $sts_cf, $sts_cf1,
            $from_date, $to_date
        ])->num_rows();

        $sql = "SELECT fm.srn
                FROM forest_clarification fm
                LEFT JOIN clarification_response fr ON fr.clar_srn = fm.srn
                WHERE fm.status = ?
                AND DATE(fr.clar_response_time) > DATE(fm.rtsdate)
                AND DATE(fm.req_date) BETWEEN ? AND ?";
        $clar_app_beyond = $this->db->query($sql, [
            $status_app, $from_date, $to_date
        ])->num_rows();

        $sql = "SELECT fm.srn
                FROM forest_clarification fm
                WHERE fm.status IN (?,?,?,?,?)
                AND CURDATE() > DATE(fm.rtsdate)
                AND DATE(fm.req_date) BETWEEN ? AND ?";
        $clar_pen_beyond = $this->db->query($sql, [
            $sts_pending, $sts_ro, $sts_ro1, $sts_cf, $sts_cf1,
            $from_date, $to_date
        ])->num_rows();

        $sql = "SELECT srn
                FROM forest_clarification
                WHERE status = ?
                AND DATE(req_date) BETWEEN ? AND ?";
        $count_clar_rej = $this->db->query($sql, [
            $sts_rejected, $from_date, $to_date
        ])->num_rows();

        $sql = "SELECT fm.srn
                FROM forest_clarification fm
                LEFT JOIN clarification_response fr ON fr.clar_srn = fm.srn
                WHERE fm.status = ?
                AND DATE(fr.clar_response_time) > DATE(fm.rtsdate)
                AND DATE(fm.req_date) BETWEEN ? AND ?";
        $clar_rej_beyond = $this->db->query($sql, [
            $sts_rejected, $from_date, $to_date
        ])->num_rows();

        $total['clarification'] = [
            'service'         => 'NOCs in respect of PLPA / Forest / Restricted lands',
            'total_rec'       => $count_total_clar,
            'total_pending'   => $count_clar_pen,
            'beyond_time_pen' => $clar_pen_beyond,
            'within_time_pen' => $count_clar_pen - $clar_pen_beyond,
            'total_approved'  => $count_clar_app,
            'beyond_time_app' => $clar_app_beyond,
            'within_time_app' => $count_clar_app - $clar_app_beyond,
            'total_rejected'  => $count_clar_rej,
            'beyond_time_rej' => $clar_rej_beyond,
            'within_time_rej' => $count_clar_rej - $clar_rej_beyond
        ];

        return $total;
    }
}
