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
		$data = [1,2,3];
		$this->template->load('basepage/base', 'content', $data);
	}
}