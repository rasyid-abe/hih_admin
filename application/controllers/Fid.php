<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$arr_branch;
class Fid extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		is_logged_id();
		date_default_timezone_set('Asia/Jakarta');
		$this->arr_branch = array();
    }

    public function index()
    {
        $data = [];
		$data['title'] = "Data FID";

		$this->db->select("branch.branch_name, fid_data.*");
		$this->db->join('branch', 'branch.branch_code = fid_data.branch');
		$data["rows"] = $this->db->get('fid_data')->result_array();

		$this->template->load('basepage/base', 'fid/base-v', $data);
    }

	public function import()
	{
		$filename = $_FILES['excelfile']['name'];
		$filedata = $_FILES['excelfile']['tmp_name'];
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filedata);
		$spreadsheet = $reader->load($filedata);
		$sheetData = $spreadsheet->getActiveSheet()->toArray();

		$this->db->select('branch_code, branch_name');
		$branch = $this->db->get('branch')->result_array();

		foreach ($branch as $key => $value) {
			$this->arr_branch[$value['branch_code']] = $value['branch_name'];
		}

		$myarray = array_map(function($sheetData) {
			if (count($sheetData) > 0) {
				if ($sheetData[1] != '' && $sheetData[1] != 'BRANCH') {
					if (in_array(trim($sheetData[1], " "), $this->arr_branch)) {
						return array(
							'branch' => array_search(trim($sheetData[1], " ") ,$this->arr_branch),
							'contract_no' => $sheetData[2],
							'customer_name' => $sheetData[3],
							'address' => $sheetData[4],
							'portfolio' => $sheetData[5],
							'principal_ammount' => (int)preg_replace("/([^0-9\\.])/i", "", $sheetData[6]),
							'status_fid' => $sheetData[7],
							'nik_sales' => $sheetData[8],
							'name_sales' => $sheetData[9],
							'nik_chm' => $sheetData[10],
							'name_chm' => $sheetData[11],
							'do_date' => date("Y-m-d H:i:s", strtotime($sheetData[12])),
							'prediction' => $sheetData[13],
							'reason' => $sheetData[14],
							'date_imported' => date('Y-m-d H:i:s'),
							'imported_by' => $this->session->userdata('id'),
						); 
					}
				}
			}
		}, $sheetData);
		$clearsheet = array_filter($myarray, fn($value) => !is_null($value) && $value !== '');

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);

		$k = 0;
		foreach ($clearsheet as $item) {
			if ($this->db->replace('fid_data', $item)) {
				$k++;
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Import data from excel failed!');
		} else {
			$this->db->trans_commit();
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success import '.$k.' datas from '.$filename.'!');
		}

		redirect('fid');
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
		$sheet->setCellValue('A1', "USER ACTIVITIES"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
		// Buat header tabel nya pada baris ke 3
		$sheet->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('B3', "NIK"); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('C3', "USER"); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('D3', "DATETIME"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$sheet->setCellValue('E3', "ACTIVITIES"); // Set kolom E3 dengan tulisan "ALAMAT"
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);

		// Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
		$this->db->order_by("log_activities.datetime", "desc");
        $this->db->select('user.nik, user.fullname, log_activities.datetime, log_activities.log');
		$this->db->join('user', 'user.nik = log_activities.nik');        
		$rows = $this->db->get('log_activities')->result();
		
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4

		foreach($rows as $data){ // Lakukan looping pada variabel siswa
		  $sheet->setCellValue('A'.$numrow, $no);
		  $sheet->setCellValue('B'.$numrow, $data->nik);
		  $sheet->setCellValue('C'.$numrow, $data->fullname);
		  $sheet->setCellValue('D'.$numrow, $data->datetime);
		  $sheet->setCellValue('E'.$numrow, $data->log);
		  
		  // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
		  $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
		  
		  $no++; // Tambah 1 setiap kali looping
		  $numrow++; // Tambah 1 setiap kali looping
		}
		// Set width kolom
		$sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
		$sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
		$sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
		$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
		$sheet->getColumnDimension('E')->setWidth(130); // Set width kolom E
		
		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$sheet->setTitle("DATA SELURUH USERS");
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Activity User.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}


}
