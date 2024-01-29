<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Term_condition extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		is_logged_id();
		date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data = [];
		$data['title'] = "Term & Condition";

        $rows = $this->db->get('term_condition')->result_array();
		$data['rows'] = $rows;

		$this->template->load('basepage/base', 'term_condition/base-v', $data);
    }

    public function view_term_condition($id)
    {
        $data = [];
        $data['title'] = "View Term & Condition";

        $doc = $this->db->get_where('term_condition', ['id' => $id])->row_array();
        $data['doc'] = $doc;
        $this->template->load('basepage/base', 'term_condition/view-v', $data);
    }

	public function add_term_condition()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('body', 'Body', 'required');

		if ($this->form_validation->run() == false) {
			$data = [];
			$data['title'] = "Add Term & Condition";

			$this->template->load('basepage/base', 'term_condition/input-v', $data);
		} else {
			$post = $this->input->post();

            $data = [
				'name' => htmlspecialchars($post['name']),
				'body' => $post['body'],
				'date_created' => date("Y-m-d H:i:s"),
				'created_by' => $this->session->userdata('id'),
				'updated_by' => $this->session->userdata('id'),
			];

            if ($this->db->insert('term_condition', $data)) {

				$logs = [
					'type' => 'term',
					'page' => 'Term & Conditions',
					'title' => $post['name'],
					'status' => 1,
					'id_content' => $this->db->insert_id(),
				];
				notif($logs);

				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success create term & condition!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed create term & condition!');
			}

			redirect('term_condition/add_term_condition');
		}
	}

    public function edit_term_condition()
	{
		$id = $this->uri->segment(3);
		$post = $this->input->post();

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('body', 'Body', 'required');

		if ($this->form_validation->run() == false) {

			$id = $post ? $post['id'] : $id;
			$row = $this->db->get_where('term_condition', ['id' => $id])->row_array();

			$data = [];
			$data['title'] = "Edit Term & Condition";
			$data['row'] = $row;
			$this->template->load('basepage/base', 'term_condition/edit-v', $data);
		} else {

			$this->db->set('name', htmlspecialchars($post['name']));
			$this->db->set('body', $post['body']);
			$this->db->set('updated_by',  $this->session->userdata('id'));
			$this->db->where('id', $post['id']);

			if ($this->db->update('term_condition')) {

				$logs = [
					'type' => 'term',
					'page' => 'Term & Conditions',
					'title' => $post['name'],
					'status' => 1,
					'id_content' => $post['id'],
				];
				notif($logs);

				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success update term & condition!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed update term & condition!');
			}

			redirect('term_condition/edit_term_condition/'.$post['id']);
		}
	}

    public function delete_term_condition($id)
	{
		$this->db->where('id', $id);
		if ($this->db->delete('term_condition')) {
			$this->db->where(['type' => 'term', 'id_content' => $id])->delete('notification');
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success deleted term & condition!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed deleted term & condition!');
		}

		redirect('term_condition');
	}
}
