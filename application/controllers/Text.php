<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Text extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		is_logged_id();
		date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data = [];
		$data['title'] = "Document Text";

        $this->db->select('a.id id, a.document_name doc_name, a.is_active active_text, b.name group');
        $this->db->from('document_text a');
        $this->db->join('group_document b', 'a.group_id=b.id', 'left');
		$rows = $this->db->get()->result_array();
		$data['rows'] = $rows;

		$this->template->load('basepage/base', 'text/base-v', $data);
    }

    public function view_text($id)
    {
        $data = [];
        $data['title'] = "View Document Text";

        $doc = $this->db->get_where('document_text', ['id' => $id])->row_array();
        $data['doc'] = $doc;
        $this->template->load('basepage/base', 'text/view-v', $data);
    }

	public function add_text()
	{
        $groups = $this->db->get_where('group_document', ['is_active' => 1])->result_array();
		$ids = [];
		foreach ($groups as $k => $v) {$ids[] = $v['id'];}

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('body', 'Body', 'required');
        $this->form_validation->set_rules('group', 'Group', 'required|in_list['.implode(',', $ids).']', ['in_list' => 'The Group Document must be selected']);

		if ($this->form_validation->run() == false) {
			$data = [];
			$data['title'] = "Add Text";
            $data['groups'] = $groups;

			$this->template->load('basepage/base', 'text/input-v', $data);
		} else {
			$post = $this->input->post();

            $data = [
				'document_name' => htmlspecialchars($post['name']),
				'body' => $post['body'],
                'group_id' => $post['group'],
				'date_created' => date("Y-m-d H:i:s"),
				'created_by' => $this->session->userdata('id'),
				'updated_by' => $this->session->userdata('id'),
			];

            if ($this->db->insert('document_text', $data)) {
				$logs = [
					'type' => 'text',
					'page' => 'ReadText',
					'title' => htmlspecialchars($post['name']),
					'id_content' => $this->db->insert_id(),
				];
				notif($logs);

				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success create document text!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed create document text!');
			}

			redirect('text/add_text');
		}
	}

    public function edit_text()
	{
		$id = $this->uri->segment(3);
		$post = $this->input->post();

        $groups = $this->db->get('group_document')->result_array();
		$ids = [];
		foreach ($groups as $k => $v) {$ids[] = $v['id'];}

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('body', 'Body', 'required');
        $this->form_validation->set_rules('group', 'Group', 'required|in_list['.implode(',', $ids).']', ['in_list' => 'The Group Document must be selected']);

		if ($this->form_validation->run() == false) {

			$id = $post ? $post['id'] : $id;
			$row = $this->db->get_where('document_text', ['id' => $id])->row_array();
            $groups = $this->db->get_where('group_document', ['is_active' => 1])->result_array();

			$data = [];
			$data['title'] = "Edit Text";
			$data['row'] = $row;
			$data['groups'] = $groups;
			$this->template->load('basepage/base', 'text/edit-v', $data);
		} else {

			$this->db->set('document_name', htmlspecialchars($post['name']));
			$this->db->set('body', $post['body']);
			$this->db->set('group_id', $post['group']);
			$this->db->set('updated_by',  $this->session->userdata('id'));
			$this->db->where('id', $post['id']);

			if ($this->db->update('document_text')) {
				$logs = [
					'type' => 'text',
					'page' => 'ReadText',
					'title' => htmlspecialchars($post['name']),
					'status' => 1,
					'id_content' => $post['id'],
				];
				notif($logs);
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success update document text!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed update document text!');
			}

			redirect('text/edit_text/'.$post['id']);
		}
	}

    public function delete_text($id)
	{
		$this->db->where('id', $id);
		if ($this->db->delete('document_text')) {
			$this->db->where(['type' => 'text', 'id_content' => $id])->delete('notification');
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success deleted document text!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed deleted document text!');
		}

		redirect('text');
	}

    public function is_active($sts, $id)
	{
		$msg = $sts == 1 ? 'Activated' : 'Deactivated';

		$this->db->set('is_active', $sts);
		$this->db->where('id', $id);

		if ($this->db->update('document_text')) {

			$this->db->where(['type' => 'text', 'id_content' => $id]);
			$this->db->update('notification', ['status' => $sts]);
			
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success '.$msg.' document text!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed '.$msg.' document text!');
		}

		redirect('text');
	}
}
