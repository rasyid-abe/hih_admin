<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class User extends CI_Controller {

	public function __construct()
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
		$data['title'] = "User Management";

		$this->db->select('*');
		$this->db->from('user a');
		$this->db->join('user_role b', 'a.role_id=b.id', 'left');
		$this->db->where('a.id !=', 1);
		$this->db->where('a.nik !=', $this->session->userdata('nik'));
		$rows = $this->db->get()->result_array();
		$data['rows'] = $rows;

		$this->template->load('basepage/base', 'user/base-v', $data);
	}

	public function profile()
	{
		$row = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();

		$this->form_validation->set_rules('fullname', 'Fullname', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'valid_email|trim');
		$this->form_validation->set_rules('phone', 'Phone', 'min_length[9]|max_length[14]|trim');

		if ($this->form_validation->run() == false) {
			$data = [];
			$data['title'] = "Profile";
			$data['row'] = $row;
			$this->template->load('basepage/base', 'user/profile-v', $data);
		} else {
			$post = $this->input->post();
			$img = $_FILES['foto']['name'];

			if ($img) {
				$config['upload_path'] = './assets/images/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['max_size'] = '2048';

				$this->upload->initialize($config);

				if ($this->upload->do_upload('foto')) {
					$old_image = $row['foto'];
					if ($old_image != 'default.png') {
						unlink(FCPATH.'assets/images/'.$old_image);
					}
					$new_image = $this->upload->data('file_name');
					$this->db->set('foto', $new_image);
				} else {
					echo '<pre>';
                    print_r($this->upload->display_errors());
                    exit();
				}
			}

			$this->db->set('fullname', htmlspecialchars($post['fullname']));
			$this->db->set('gender', htmlspecialchars($post['gender']));
			$this->db->set('email', htmlspecialchars($post['email']));
			$this->db->set('phone', htmlspecialchars($post['phone']));
			$this->db->set('updated_by',  $this->session->userdata('id'));
			$this->db->where('nik', $post['nik']);

			if ($this->db->update('user')) {
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success change password!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Change password is failed!');
			}

			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Your profile have been updated!');
			redirect('user/profile');
		}
	}

	public function password()
	{
		$row = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();

		$this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
		$this->form_validation->set_rules('new_password', 'New Password', 'required|trim|min_length[8]|matches[match_password]');
		$this->form_validation->set_rules('match_password', 'Confirm New Password', 'required|trim|min_length[8]|matches[new_password]');
		if ($this->form_validation->run() == false) {
			$data = [];
			$data['title'] = "Change Password";
			$this->template->load('basepage/base', 'user/password-v', $data);
		} else {
			$post = $this->input->post();
			$new_password = $post['new_password'];
			$current = $post['current_password'];

			if (!password_verify($current, $row['password'])) {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Current password is wrong!');
			} else {
				if ($new_password == $current) {
					$this->session->set_flashdata('alert_head', 'error');
					$this->session->set_flashdata('alert_msg', 'New password is same as current password!');
				} else {
					$hash_pass = password_hash($new_password, PASSWORD_DEFAULT);

					$this->db->set('password', $hash_pass);
					$this->db->where('nik', $row['nik']);
					if ($this->db->update('user')) {
						$this->session->set_flashdata('alert_head', 'success');
						$this->session->set_flashdata('alert_msg', 'Success change password!');
					} else {
						$this->session->set_flashdata('alert_head', 'error');
						$this->session->set_flashdata('alert_msg', 'Change password is failed!');
					}
				}
			}


			redirect('user/password');
		}
	}

	public function add_user()
	{
		$role = $this->db->get('user_role')->result_array();
		$idrole = [];
		foreach ($role as $k => $v) {$idrole[] = $v['id'];}

		$this->form_validation->set_rules('fullname', 'Fullname', 'required|trim');
		$this->form_validation->set_rules('nik', 'NIK', 'required|trim|is_unique[user.nik]', ['is_unique' => 'This NIK has already registered!']);
		$this->form_validation->set_rules('email', 'Email', 'valid_email|trim');
		$this->form_validation->set_rules('phone', 'Phone', 'max_length[14]|trim');
		$this->form_validation->set_rules('role', 'Role', 'required|in_list['.implode(',', $idrole).']', ['in_list' => 'The User Role must be selected']);

		if ($this->form_validation->run() == false) {
			$data = [];

			$data['title'] = "Add User";
			$data['role'] = $role;

			$this->template->load('basepage/base', 'user/input-v', $data);
		} else {
			$post = $this->input->post();
			$data = [
				'fullname' => htmlspecialchars($post['fullname']),
				'gender' => htmlspecialchars($post['gender']),
				'nik' => htmlspecialchars($post['nik']),
				'email' => htmlspecialchars($post['email']),
				'phone' => htmlspecialchars($post['phone']),
				'password' => password_hash('12345678', PASSWORD_DEFAULT),
				'foto' => 'default.png',
				'date_expired' => date("Y-m-d"),
				'date_created' => date("Y-m-d H:i:s"),
				'created_by' => $this->session->userdata('id'),
				'updated_by' => $this->session->userdata('id'),
				'role_id' => $post['role'],
			];

			if ($this->db->insert('user', $data)) {
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success change password!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Change password is failed!');
			}

			redirect('user/add_user');
		}
	}

	public function edit_user()
	{
		$nik = $this->uri->segment(3);
		$post = $this->input->post();
		$role = $this->db->get('user_role')->result_array();

		$this->form_validation->set_rules('fullname', 'Fullname', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'valid_email|trim');
		$this->form_validation->set_rules('phone', 'Phone', 'min_length[9]|max_length[14]|trim');

		if ($this->form_validation->run() == false) {

			$nik = $post ? $post['nik'] : $nik;
			$row = $this->db->get_where('user', ['nik' => $nik])->row_array();

			$data = [];
			$data['title'] = "Edit User";
			$data['row'] = $row;
			$data['role'] = $role;
			$this->template->load('basepage/base', 'user/edit-v', $data);
		} else {

			$this->db->set('fullname', htmlspecialchars($post['fullname']));
			$this->db->set('gender', htmlspecialchars($post['gender']));
			$this->db->set('email', htmlspecialchars($post['email']));
			$this->db->set('phone', htmlspecialchars($post['phone']));
			$this->db->set('role_id', $post['role']);
			$this->db->set('updated_by',  $this->session->userdata('id'));
			$this->db->where('nik', $post['nik']);

			if ($this->db->update('user')) {
				$this->session->set_flashdata('alert_head', 'success');
				$this->session->set_flashdata('alert_msg', 'Success update user!');
			} else {
				$this->session->set_flashdata('alert_head', 'error');
				$this->session->set_flashdata('alert_msg', 'Failed update user!');
			}

			redirect('user/edit_user/'.$post['nik']);
		}
	}

	public function delete_user($nik)
	{
		# cascade delete foto
		$foto = $this->db->get_where('user', ['nik' => $nik])->row('foto');
		if ($foto != 'default.png') {
			unlink(FCPATH.'assets/images/'.$foto);
		}

		$this->db->where('nik', $nik);
		if ($this->db->delete('user')) {
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success deleted user!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed deleted user!');
		}

		redirect('user');
	}

	public function is_active($sts, $nik)
	{
		$msg = $sts == 1 ? 'Activated' : 'Deactivated';
		$exp_date = date('Y-m-d', strtotime('+1 month'));

		if ($sts == 1) {
			$this->db->set('date_expired', $exp_date);
		}
		$this->db->set('is_active', $sts);
		$this->db->where('nik', $nik);

		if ($this->db->update('user')) {
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success '.$msg.' user!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed '.$msg.' user!');
		}

		redirect('user');
	}

	public function reset_password($nik)
	{
		$this->db->set('password', password_hash('12345678', PASSWORD_DEFAULT));
		$this->db->where('nik', $nik);

		if ($this->db->update('user')) {
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success reset password user!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed reset password user!');
		}

		redirect('user');
	}

	public function export(){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		$style_col = [
		  'font' => ['bold' => true], // Set font nya jadi bold
		  'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		  ],
		  'borders' => [
			'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
			'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
			'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
			'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
		  ]
		];
		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = [
		  'alignment' => [
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		  ],
		  'borders' => [
			'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
			'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
			'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
			'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
		  ]
		];
		$sheet->setCellValue('A1', "DATA USER"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
		// Buat header tabel nya pada baris ke 3
		$sheet->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('B3', "NIK"); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('C3', "NAMA"); // Set kolom C3 dengan tulisan "NAMA"
		$sheet->setCellValue('D3', "JENIS KELAMIN"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$sheet->setCellValue('E3', "EMAIL"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('F3', "NO. HP"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('G3', "ROLE"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('H3', "STATUS"); // Set kolom E3 dengan tulisan "ALAMAT"
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);
		$sheet->getStyle('F3')->applyFromArray($style_col);
		$sheet->getStyle('G3')->applyFromArray($style_col);
		$sheet->getStyle('H3')->applyFromArray($style_col);

		// Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
		$this->db->select('*');
		$this->db->from('user a');
		$this->db->join('user_role b', 'a.role_id=b.id', 'left');
		$this->db->where('a.id !=', 1);
		$rows = $this->db->get()->result();
		
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4

		foreach($rows as $data){ // Lakukan looping pada variabel siswa
		  $sheet->setCellValue('A'.$numrow, $no);
		  $sheet->setCellValue('B'.$numrow, $data->nik);
		  $sheet->setCellValue('C'.$numrow, $data->fullname);
		  $sheet->setCellValue('D'.$numrow, $data->gender == 1 ? 'Laki-laki' : 'Perempuan' );
		  $sheet->setCellValue('E'.$numrow, $data->email);
		  $sheet->setCellValue('F'.$numrow, $data->phone);
		  $sheet->setCellValue('G'.$numrow, $data->name);
		  $sheet->setCellValue('H'.$numrow, $data->is_active > 0 ? 'Akfif' : 'Nonaktif');
		  
		  // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
		  $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('H'.$numrow)->applyFromArray($style_row);
		  
		  $no++; // Tambah 1 setiap kali looping
		  $numrow++; // Tambah 1 setiap kali looping
		}
		// Set width kolom
		$sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
		$sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
		$sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
		$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
		$sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
		$sheet->getColumnDimension('F')->setWidth(30); // Set width kolom E
		$sheet->getColumnDimension('G')->setWidth(30); // Set width kolom E
		$sheet->getColumnDimension('H')->setWidth(30); // Set width kolom E
		
		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$sheet->setTitle("DATA SELURUH USERS");
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Data User HIH.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	  }
	
}
