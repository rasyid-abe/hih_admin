<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slide extends CI_Controller {

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
		$data['title'] = "Slide Banner Document";

		$rows = $this->db->get('slide_banner')->result_array();
		$data['rows'] = $rows;

		$this->template->load('basepage/base', 'slide/base-v', $data);
    }


	public function add_slide()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		if (empty($_FILES['image']['name'])){
			$this->form_validation->set_rules('image', 'Image', 'required');
		}

		if ($this->form_validation->run() == false) {
			$data = [];
			
			$data['title'] = "Add Slide Banner";
			$this->template->load('basepage/base', 'slide/input-v', $data);
		} else {
			$post = $this->input->post();

			$img = $_FILES['image']['name'];
			$ext = explode('.', $img);
			if ($img) {
				$config['upload_path'] = './assets/slides/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['max_size'] = '2048';
				$config['file_name'] = str_replace(' ', '_', $post['name']).'.'.end($ext);

				$this->upload->initialize($config);

				if (!$this->upload->do_upload('image')) {
					echo '<pre>';
                    print_r($this->upload->display_errors());
                    exit();
				}
			}

            $data = [
				'image' => str_replace(' ', '_', $post['name']).'.'.end($ext),
				'description' => htmlspecialchars($post['description']),
				'date_created' => date("Y-m-d H:i:s"),
				'created_by' => $this->session->userdata('id'),
				'updated_by' => $this->session->userdata('id'),
			];

            if ($this->db->insert('slide_banner', $data)) {
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success create slide!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed create slide!');
			}

			redirect('slide/add_slide');
		}
	}

    public function delete_slide($id)
	{
		$foto = $this->db->get_where('slide_banner', ['id' => $id])->row('image');
		if ($foto) {
			unlink(FCPATH.'assets/slides/'.$foto);
		}

		$this->db->where('id', $id);
		if ($this->db->delete('slide_banner')) {
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success deleted slide!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed deleted slide!');
		}

		redirect('slide');
	}

    public function is_active($sts, $id)
	{
		$msg = $sts == 1 ? 'Activated' : 'Deactivated';

		$this->db->set('is_active', $sts);
		$this->db->where('id', $id);

		if ($this->db->update('slide_banner')) {
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success '.$msg.' slide!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed '.$msg.' slide!');
		}

		redirect('slide');
	}
}
