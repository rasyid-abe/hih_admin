<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		is_logged_id();
		date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data = [];
		$data['title'] = "User Activities";

        
		$rows = $this->db->get('log_activities')->result_array();
		$data['rows'] = $rows;

		$this->template->load('basepage/base', 'activity/base-v', $data);
    }

}
