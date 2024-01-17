<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		is_logged_id();
		date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data = [];
		$data['title'] = "Branch Management";

		$rows = $this->db->get('branch')->result_array();
		$data['rows'] = $rows;

		$this->template->load('basepage/base', 'branch/base-v', $data);
    }


	public function add_branch()
	{
		$areas = get_area();

		$this->form_validation->set_rules('code', 'Branch Code', 'required|trim|is_unique[branch.branch_code]', ['is_unique' => 'This Branch Code has already registered!']);
		$this->form_validation->set_rules('name', 'Branch Name', 'required|trim');
		$this->form_validation->set_rules('area', 'Branch Area', 'required|in_list['.implode(',', $areas).']', ['in_list' => 'The Branch Area must be selected']);

		if ($this->form_validation->run() == false) {
			$data = [];
			$data['title'] = "Add Branch";
			$data['areas'] = $areas;
			$this->template->load('basepage/base', 'branch/input-v', $data);
		} else {
			$post = $this->input->post();
			$data = [
				'branch_code' => htmlspecialchars($post['code']),
				'branch_name' => htmlspecialchars($post['name']),
				'branch_area' => htmlspecialchars($post['area']),
				'branch_description' => htmlspecialchars($post['description']),
				'date_created' => date("Y-m-d H:i:s"),
				'created_by' => $this->session->userdata('id'),
				'updated_by' => $this->session->userdata('id'),
			];
			
            if ($this->db->insert('branch', $data)) {
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success create branch!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed create branch!');
			}

			redirect('branch/add_branch');
		}
	}

    public function edit_branch()
	{
		$id = $this->uri->segment(3);
		$post = $this->input->post();
		$areas = get_area();
		$row = $this->db->get_where('branch', ['id' => count($post) > 0 ? $post['id'] : $id])->row_array();

		$is_unique = '';
		if (count($post) > 0) {
			if ($post['code'] != $row['branch_code']) {
				$is_unique =  '|is_unique[branch.branch_code]';
			}
		}

		$this->form_validation->set_rules('code', 'Branch Code', 'required|trim'.$is_unique, ['is_unique' => 'This Branch Code has already registered!']);
		$this->form_validation->set_rules('name', 'Branch Name', 'required|trim');
		$this->form_validation->set_rules('area', 'Branch Area', 'required|in_list['.implode(',', $areas).']', ['in_list' => 'The Branch Area must be selected']);

		if ($this->form_validation->run() == false) {

			$id = $post ? $post['id'] : $id;

			$data = [];
			$data['title'] = "Edit Branch";
			$data['row'] = $row;
			$data['areas'] = $areas;

			$this->template->load('basepage/base', 'branch/edit-v', $data);
		} else {

			$this->db->set('branch_code ', htmlspecialchars($post['code']));
			$this->db->set('branch_name', htmlspecialchars($post['name']));
			$this->db->set('branch_area', htmlspecialchars($post['area']));
			$this->db->set('branch_description', htmlspecialchars($post['description']));
			$this->db->set('date_updated', date("Y-m-d H:i:s"));
			$this->db->set('updated_by',  $this->session->userdata('id'));
			$this->db->where('id', $post['id']);

			if ($this->db->update('branch')) {
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success update branch!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed update branch!');
			}

			redirect('branch/edit_branch/'.$post['id']);
		}
	}

    public function delete_branch($id)
	{
        # cascade inactive user
        $this->db->set('is_active', 0);
        $this->db->set('branch_id', 0);
        $this->db->where('branch_id', $id);
        $this->db->update('user');

		$this->db->where('id', $id);
		if ($this->db->delete('branch')) {
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success deleted branch!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed deleted branch!');
		}

		redirect('branch');
	}

	public function is_active($sts, $id)
	{
		$msg = $sts == 1 ? 'Activated' : 'Deactivated';
		$this->db->set('is_active', $sts);
		$this->db->where('id', $id);

		if ($this->db->update('branch')) {
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success '.$msg.' branch!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed '.$msg.' branch!');
		}

		redirect('branch');
	}

}
