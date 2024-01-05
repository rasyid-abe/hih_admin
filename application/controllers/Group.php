<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		is_logged_id();
		date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data = [];
		$data['title'] = "Group Document";

		$rows = $this->db->get('group_document')->result_array();
		$data['rows'] = $rows;

		$this->template->load('basepage/base', 'group/base-v', $data);
    }


	public function add_group()
	{
		$this->form_validation->set_rules('name', 'Group', 'required|trim');

		if ($this->form_validation->run() == false) {
			$data = [];

			$json = file_get_contents(base_url().'assets/js/data.json');
			$obj  = json_decode($json);

			$data['icons'] = $obj->icons;

			$data['title'] = "Add Group";
			$this->template->load('basepage/base', 'group/input-v', $data);
		} else {
			$post = $this->input->post();

            $data = [
				'name' => htmlspecialchars($post['name']),
				'icon' => htmlspecialchars($post['icon']),
				'description' => htmlspecialchars($post['description']),
				'date_created' => date("Y-m-d H:i:s"),
				'created_by' => $this->session->userdata('id'),
				'updated_by' => $this->session->userdata('id'),
			];

            if ($this->db->insert('group_document', $data)) {
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success create group!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed create group!');
			}

			redirect('group/add_group');
		}
	}

    public function edit_group()
	{
		$id = $this->uri->segment(3);
		$post = $this->input->post();

		$this->form_validation->set_rules('name', 'Group', 'required|trim');

		if ($this->form_validation->run() == false) {

			$data = [];
			$id = $post ? $post['id'] : $id;
			$row = $this->db->get_where('group_document', ['id' => $id])->row_array();

			$json = file_get_contents(base_url().'assets/js/data.json');
			$obj  = json_decode($json);

			$data['icons'] = $obj->icons;

			$data['title'] = "Edit Group";
			$data['row'] = $row;
			$this->template->load('basepage/base', 'group/edit-v', $data);
		} else {

			// echo '<pre>';
			// print_r($post);
			// die;

			$this->db->set('name', htmlspecialchars($post['name']));
			$this->db->set('icon', htmlspecialchars($post['icon']));
			$this->db->set('description', htmlspecialchars($post['description']));
			$this->db->set('updated_by',  $this->session->userdata('id'));
			$this->db->where('id', $post['id']);

			if ($this->db->update('group_document')) {
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success update group!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed update group!');
			}

			redirect('group/edit_group/'.$post['id']);
		}
	}

    public function delete_group($id)
	{
        # cascade inactive text
        $this->db->set('is_active', 0);
        $this->db->set('group_id', 0);
        $this->db->where('group_id', $id);
        $this->db->update('document_text');

        # cascade inactive pdf
        $this->db->set('is_active', 0);
        $this->db->set('group_id', 0);
        $this->db->where('group_id', $id);
        $this->db->update('document_pdf');

		$this->db->where('id', $id);
		if ($this->db->delete('group_document')) {
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success deleted group!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed deleted group!');
		}

		redirect('group');
	}

    public function is_active($sts, $id)
	{
		$msg = $sts == 1 ? 'Activated' : 'Deactivated';

		$this->db->set('is_active', $sts);
		$this->db->where('id', $id);

		if ($this->db->update('group_document')) {
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success '.$msg.' group!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed '.$msg.' group!');
		}

		redirect('group');
	}
}
