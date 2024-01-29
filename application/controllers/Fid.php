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
				$logs = [
					'type' => 'fid',
					'page' => 'FidForm',
					'status' => 1,
					'title' => $item['customer_name'].'-'.$item['contract_no'],
					'id_content' =>  $this->db->insert_id(),
				];
				notif($logs);

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
		$sheet->setCellValue('A1', "DATA FID"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
		// Buat header tabel nya pada baris ke 3
		$sheet->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('B3', "BRANCH NAME"); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('C3', "CONTRACT NO"); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('D3', "CUSTOMER NAME"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$sheet->setCellValue('E3', "ADDRESS"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('F3', "PORTFOLIO"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('G3', "PRINCIPAL AMMOUNT"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('H3', "STATUS"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('I3', "NIK SALES"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('J3', "NAME SALES"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('K3', "DO DATE SALES"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('L3', "PREDICTION SALES"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('M3', "REASON SALES"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('N3', "NIK CHM"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('O3', "NAME CHM"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('P3', "DO DATE CHM"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('Q3', "PREDICTION CHM"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('R3', "REASON CHM"); // Set kolom E3 dengan tulisan "ALAMAT"
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);
		$sheet->getStyle('F3')->applyFromArray($style_col);
		$sheet->getStyle('G3')->applyFromArray($style_col);
		$sheet->getStyle('H3')->applyFromArray($style_col);
		$sheet->getStyle('I3')->applyFromArray($style_col);
		$sheet->getStyle('J3')->applyFromArray($style_col);
		$sheet->getStyle('K3')->applyFromArray($style_col);
		$sheet->getStyle('L3')->applyFromArray($style_col);
		$sheet->getStyle('M3')->applyFromArray($style_col);
		$sheet->getStyle('N3')->applyFromArray($style_col);
		$sheet->getStyle('O3')->applyFromArray($style_col);
		$sheet->getStyle('P3')->applyFromArray($style_col);
		$sheet->getStyle('Q3')->applyFromArray($style_col);
		$sheet->getStyle('R3')->applyFromArray($style_col);

		// Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
		$this->db->select("branch.branch_name, fid_data.*");
		$this->db->join('branch', 'branch.branch_code = fid_data.branch');
		$rows = $this->db->get('fid_data')->result();
		
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4

		foreach($rows as $data){ // Lakukan looping pada variabel siswa
		  $sheet->setCellValue('A'.$numrow, $no);
		  $sheet->setCellValue('B'.$numrow, $data->branch_name);
		  $sheet->setCellValue('C'.$numrow, strval($data->contract_no));
		  $sheet->setCellValue('D'.$numrow, $data->customer_name);
		  $sheet->setCellValue('E'.$numrow, $data->address);
		  $sheet->setCellValue('F'.$numrow, $data->portfolio);
		  $sheet->setCellValue('G'.$numrow, $data->principal_ammount);
		  $sheet->setCellValue('H'.$numrow, $data->status_fid);
		  $sheet->setCellValue('I'.$numrow, $data->nik_sales);
		  $sheet->setCellValue('J'.$numrow, $data->name_sales);
		  $sheet->setCellValue('K'.$numrow, $data->do_date_sales);
		  $sheet->setCellValue('L'.$numrow, $data->prediction_sales);
		  $sheet->setCellValue('M'.$numrow, $data->reason_sales);
		  $sheet->setCellValue('N'.$numrow, $data->nik_chm);
		  $sheet->setCellValue('O'.$numrow, $data->name_chm);
		  $sheet->setCellValue('P'.$numrow, $data->do_date_chm);
		  $sheet->setCellValue('Q'.$numrow, $data->prediction_chm);
		  $sheet->setCellValue('R'.$numrow, $data->reason_chm);
		  
		  // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
		  $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('H'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('I'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('J'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('K'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('L'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('M'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('N'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('O'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('P'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('Q'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('R'.$numrow)->applyFromArray($style_row);
		  
		  $no++; // Tambah 1 setiap kali looping
		  $numrow++; // Tambah 1 setiap kali looping
		}
		// Set width kolom
		$sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
		$sheet->getColumnDimension('B')->setWidth(40); // Set width kolom B
		$sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
		$sheet->getColumnDimension('D')->setWidth(40); // Set width kolom D
		$sheet->getColumnDimension('E')->setWidth(50); // Set width kolom E
		$sheet->getColumnDimension('F')->setWidth(30); // Set width kolom E
		$sheet->getColumnDimension('G')->setWidth(15); // Set width kolom E
		$sheet->getColumnDimension('H')->setWidth(10); // Set width kolom E
		$sheet->getColumnDimension('I')->setWidth(10); // Set width kolom E
		$sheet->getColumnDimension('J')->setWidth(40); // Set width kolom E
		$sheet->getColumnDimension('K')->setWidth(20); // Set width kolom E
		$sheet->getColumnDimension('L')->setWidth(20); // Set width kolom E
		$sheet->getColumnDimension('M')->setWidth(100); // Set width kolom E
		$sheet->getColumnDimension('N')->setWidth(10); // Set width kolom E
		$sheet->getColumnDimension('O')->setWidth(40); // Set width kolom E
		$sheet->getColumnDimension('P')->setWidth(20); // Set width kolom E
		$sheet->getColumnDimension('Q')->setWidth(20); // Set width kolom E
		$sheet->getColumnDimension('R')->setWidth(100); // Set width kolom E
		
		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$sheet->setTitle("DATA FID");
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Data FID.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function delete_fid($id)
	{
		$this->db->where('id', $id);
		if ($this->db->delete('fid_data')) {
			$this->db->where(['type' => 'fid', 'id_content' => $id])->delete('notification');
			$this->session->set_flashdata('alert_head', 'success');
			$this->session->set_flashdata('alert_msg', 'Success deleted data!');
		} else {
			$this->session->set_flashdata('alert_head', 'error');
			$this->session->set_flashdata('alert_msg', 'Failed deleted data!');
		}

		redirect('fid');
	}

	public function detail()
	{
		$id = $this->input->post('id');
		$this->db->select("branch.branch_name, fid_data.*");
		$this->db->join('branch', 'branch.branch_code = fid_data.branch');
		$data = $this->db->get_where('fid_data', ['fid_data.id'=> $id])->row_array();
		echo json_encode($data);
	}

}
