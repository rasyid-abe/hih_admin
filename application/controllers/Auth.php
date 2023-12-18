<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index($alert = '')
	{
		if ($this->session->userdata('nik')) {
			redirect('home');
		}
		$this->template->load('basepage/login', 'content', $alert != '' ? ['alert' => 'alert'] : '');
	}

	public function login()
	{
		$post = $this->input->post();

		$nik = $this->input->post('nik');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['nik' => $nik, 'role_id' => 1])->row_array();
		if ($user) {
			if (password_verify($password, $user['password'])) {
				$data = [
					'id' => $user['id'],
					'nik' => $user['nik'],
					'fullname' => $user['fullname'],
					'foto' => $user['foto']
				];
				$this->session->set_userdata($data);

				redirect('home');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Wrong password!');
			}
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Account is not registered!');
		}

		redirect('auth');
	}

	public function Logout()
	{
		$this->session->unset_userdata('nik');
		$this->session->unset_userdata('fullname');
		$this->session->unset_userdata('foto');

		$this->session->set_flashdata('alert_head', 'success');
		$this->session->set_flashdata('alert_msg', 'You have been logged out!');

		redirect('auth');
	}
}
