<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		is_logged_id();
		date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data = [];
		$data['title'] = "Role Management";

		$rows = $this->db->get('user_role')->result_array();
		$data['rows'] = $rows;

		$this->template->load('basepage/base', 'role/base-v', $data);
    }


	public function add_role()
	{
		$this->form_validation->set_rules('role', 'Role', 'required|trim');

		if ($this->form_validation->run() == false) {
			$data = [];
			$data['title'] = "Add Role";
			$this->template->load('basepage/base', 'role/input-v', $data);
		} else {
			$post = $this->input->post();
			$data = [
				'name' => htmlspecialchars($post['role']),
				'description' => htmlspecialchars($post['description']),
				'date_created' => date("Y-m-d H:i:s"),
				'created_by' => $this->session->userdata('id'),
				'updated_by' => $this->session->userdata('id'),
			];

            if ($this->db->insert('user_role', $data)) {
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success create role!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed create role!');
			}

			redirect('role/add_role');
		}
	}

    public function edit_role()
	{
		$id = $this->uri->segment(3);
		$post = $this->input->post();

		$this->form_validation->set_rules('role', 'Role', 'required|trim');

		if ($this->form_validation->run() == false) {

			$id = $post ? $post['id'] : $id;
			$row = $this->db->get_where('user_role', ['id' => $id])->row_array();

			$data = [];
			$data['title'] = "Edit Role";
			$data['row'] = $row;
			$this->template->load('basepage/base', 'role/edit-v', $data);
		} else {

			$this->db->set('name', htmlspecialchars($post['role']));
			$this->db->set('description', htmlspecialchars($post['description']));
			$this->db->set('updated_by',  $this->session->userdata('id'));
			$this->db->where('id', $post['id']);

			if ($this->db->update('user_role')) {
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success update role!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed update role!');
			}

			redirect('role/edit_role/'.$post['id']);
		}
	}

    public function delete_role($id)
	{
        # cascade inactive user
        $this->db->set('is_active', 0);
        $this->db->set('role_id', 0);
        $this->db->where('role_id', $id);
        $this->db->update('user');

		$this->db->where('id', $id);
		if ($this->db->delete('user_role')) {
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success deleted role!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed deleted role!');
		}

		redirect('role');
	}

    public function config_role($id, $name)
    {
        $data = [];
		$data['title'] = "Role Access Configuration";

        $groups = $this->db->get_where('group_document', ['is_active' => 1]);
		$data['groups'] = $groups->result_array();
        $data['id_role'] = $id;
        $data['name_role'] = $name;

		$this->template->load('basepage/base', 'role/config-v', $data);
    }

    public function change_access()
    {
        $post = $this->input->post();

        $data = [
            'id_role' => $post['role'],
            'id_group' => $post['group'],
        ];

        $access = $this->db->get_where('role_access', $data);
        if ($access->num_rows() < 1) {
            $this->db->insert('role_access', $data);
        } else {
            $this->db->delete('role_access', $data);
        }

        $this->session->set_flashdata('alert_head', 'info');
        $this->session->set_flashdata('alert_msg', 'Success change role access!');
    }
}
