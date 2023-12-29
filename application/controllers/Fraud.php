<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fraud extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		is_logged_id();
		date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data = [];
		$data['title'] = "Fraud Report";

		$rows = $this->db->get('fraud_report')->result_array();
		$data['rows'] = $rows;

		$this->template->load('basepage/base', 'fraud/base-v', $data);
    }

}
