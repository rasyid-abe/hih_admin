<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_logged_id();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data = [];

		$data['pdf'] = $this->db->get('document_pdf')->num_rows();
		$data['text'] = $this->db->get('document_text')->num_rows();
		$data['fraud'] = $this->db->get('fraud_report')->num_rows();
		$data['user'] = $this->db->get('user')->num_rows();

		$data['activities'] = $this->db->get('log_activities')->result_array();

		$data['title'] = 'Dashboard';
		$this->template->load('basepage/base', 'home/dashboard-v', $data);
	}
}
