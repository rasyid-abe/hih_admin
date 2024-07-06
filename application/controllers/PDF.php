<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		is_logged_id();
		date_default_timezone_set('Asia/Jakarta');
        $this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'upload'));
    }

    public function index()
    {
        $data = [];
		$data['title'] = "Document PDF";

        $this->db->select('a.id id, a.file_name, a.is_active active_pdf, b.name group');
        $this->db->from('document_pdf a');
        $this->db->join('group_document b', 'a.group_id=b.id', 'left');
		$rows = $this->db->get()->result_array();
		$data['rows'] = $rows;

		$this->template->load('basepage/base', 'pdf/base-v', $data);
    }

    public function view_pdf($id)
    {
        $data = [];

        $pdf = $this->db->get_where('document_pdf', ['id' => $id])->row_array();
        $data['pdf'] = $pdf;
        $data['title'] = $pdf['file_name'];
        $this->template->load('basepage/base', 'pdf/view-v', $data);
    }

	public function add_pdf()
	{
        $groups = $this->db->get_where('group_document', ['is_active' => 1])->result_array();
		$ids = [];
		foreach ($groups as $k => $v) {$ids[] = $v['id'];}

		$this->form_validation->set_rules('file_name', 'Name', 'required|trim|is_unique[document_pdf.file_name]', ['is_unique' => 'This Document is already exists!']);
		$this->form_validation->set_rules('description', 'Description', 'required|trim');
        if (empty($_FILES['pdf']['name'])) {
            $this->form_validation->set_rules('pdf', 'Document PDF', 'required');
        }
        $this->form_validation->set_rules('group', 'Group', 'required|in_list['.implode(',', $ids).']', ['in_list' => 'The Group Document must be selected']);

		if ($this->form_validation->run() == false) {
			$data = [];
			$data['title'] = "Upload PDF";
            $data['groups'] = $groups;

			$this->template->load('basepage/base', 'pdf/input-v', $data);
		} else {
			$post = $this->input->post();
            $docpdf = $_FILES['pdf']['name'];

			if ($docpdf) {
				$config['upload_path'] = './assets/documents/';
				$config['allowed_types'] = 'pdf';
				$config['max_size'] = '8192';
                $config['file_name'] = str_replace(' ', '_', htmlspecialchars($post['file_name'])).'.pdf';

				$this->upload->initialize($config);
				if (!$this->upload->do_upload('pdf')) {
                    echo '<pre>';
                    print_r($this->upload->display_errors());
                    exit();
				}
			}

            $data = [
				'file_name' => htmlspecialchars($post['file_name']),
				'description' => $post['description'],
                'group_id' => $post['group'],
				'date_uploaded' => date("Y-m-d H:i:s"),
				'uploaded_by' => $this->session->userdata('id'),
				'replaced_by' => $this->session->userdata('id'),
			];

            if ($this->db->insert('document_pdf', $data)) {
				$logs = [
					'type' => 'pdf',
					'page' => 'ReadPdf',
					'title' => htmlspecialchars($post['file_name']),
					'id_content' => $this->db->insert_id(),
				];
				notif($logs);

				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success upload document pdf!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed upload document pdf!');
			}
            //
			redirect('pdf/add_pdf');
		}
	}

    public function edit_pdf()
	{
		$id = $this->uri->segment(3);
		$post = $this->input->post();

        $groups = $this->db->get('group_document')->result_array();
		$ids = [];
		foreach ($groups as $k => $v) {$ids[] = $v['id'];}

        if (isset($post['file_name']) && ($post['old_name'] != $post['file_name'])) {
            $this->form_validation->set_rules('file_name', 'Name', 'required|trim|is_unique[document_pdf.file_name]', ['is_unique' => 'This Document is already exists!']);
        } else {
            $this->form_validation->set_rules('file_name', 'Name', 'required|trim');
        }
		$this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('group', 'Group', 'required|in_list['.implode(',', $ids).']', ['in_list' => 'The Group Document must be selected']);

		if ($this->form_validation->run() == false) {

			$id = $post ? $post['id'] : $id;
			$row = $this->db->get_where('document_pdf', ['id' => $id])->row_array();
            $groups = $this->db->get_where('group_document', ['is_active' => 1])->result_array();

			$data = [];
			$data['title'] = "Edit Document PDF";
			$data['row'] = $row;
			$data['groups'] = $groups;
			$this->template->load('basepage/base', 'pdf/edit-v', $data);
		} else {
            $docpdf = $_FILES['pdf']['name'];

			if ($docpdf) {
                unlink(FCPATH.'assets/documents/'.str_replace(' ', '_', $post['old_name']).'.pdf');

				$config['upload_path'] = './assets/documents/';
                $config['allowed_types'] = 'pdf';
				$config['max_size'] = '8192';
                $config['file_name'] = str_replace(' ', '_', $post['file_name']).'.pdf';

				$this->upload->initialize($config);
				if (!$this->upload->do_upload('pdf')) {
                    echo '<pre>';
                    print_r($this->upload->display_errors());
                    exit();
				}
			}

			$this->db->set('file_name', $post['file_name']);
			$this->db->set('description', $post['description']);
			$this->db->set('group_id', $post['group']);
			$this->db->set('replaced_by',  $this->session->userdata('id'));
			$this->db->where('id', $post['id']);

			if ($this->db->update('document_pdf')) {
                if (!$docpdf) {
					$logs = [
						'type' => 'pdf',
						'page' => 'ReadPdf',
						'title' => htmlspecialchars($post['file_name']),
						'status' => 1,
						'id_content' => $post['id'],
					];
					notif($logs);

                    rename(FCPATH.'assets/documents/'.str_replace(' ', '_', $post['old_name']).'.pdf', FCPATH.'assets/documents/'.str_replace(' ', '_', $post['file_name']).'.pdf');
                }

				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success update document pdf!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed update document pdf!');
			}

			redirect('pdf/edit_pdf/'.$post['id']);
		}
	}

    public function delete_pdf($id)
	{
		$pdf = $this->db->get_where('document_pdf', ['id' => $id])->row('file_name');
		if ($pdf) {
			unlink(FCPATH.'assets/documents/'.str_replace(' ', '_', $pdf).'.pdf');
		}

		$this->db->where('id', $id);
		if ($this->db->delete('document_pdf')) {
			$this->db->where(['type' => 'pdf', 'id_content' => $id])->delete('notification');
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success deleted document pdf!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed deleted document pdf!');
		}

		redirect('pdf');
	}

    public function is_active($sts, $id)
	{
		$msg = $sts == 1 ? 'Activated' : 'Deactivated';

		$this->db->set('is_active', $sts);
		$this->db->where('id', $id);

		if ($this->db->update('document_pdf')) {

			$this->db->where(['type' => 'pdf', 'id_content' => $id]);
			$this->db->update('notification', ['status' => $sts]);

			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success '.$msg.' document pdf!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed '.$msg.' document pdf!');
		}

		redirect('pdf');
	}
}
