<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Branch extends MY_Controller
{

	public $layout_columns =[]; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/branch_rpt_model');
        $this->load->model('msbranches_model');

		$this->layout_columns = [
            ['layout' => 1, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 1, 'label'=>'Kode', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Nama Cabang', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Alamat', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Email', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'PIC', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Token', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Conn String', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Blocked', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
		];

	}

	public function index()
	{
		$this->loadForm();
	}

	public function loadForm() {
		$this->load->library('menus');
						
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$fin_branch_id = 0;

		$this->data["fin_branch_id"] = $fin_branch_id;
		$this->data["mystatus"]="OK";
		$this->data["layout_columns"] = $this->layout_columns;
		

		$side_filter = $this->parser->parse('reports/branch/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "BRANCH LIST REPORT";
		$mode = "Report";
		$report_filterbar = $this->parser->parse('inc/report_filterbar', $this->data, true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = null; // $this->parser->parse('template/standardList', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = null;
		$this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
		$this->data['MAIN_HEADER'] = $main_header;
		$this->data['MAIN_SIDEBAR'] = $main_sidebar;
		$this->data['REPORT_FILTERBAR'] = $report_filterbar;
		$this->data['REPORT_CONTENT'] = $page_content;
		$this->data['REPORT_FOOTER'] = $main_footer;
		$this->parser->parse('template/mainReport', $this->data);
	}

	//function untuk validasi form parameter report (jika ada parameter yg tidak boleh dikosongkan sesuai di model)
	public function process() {
		// print_r('testing ajx-process');
		$this->load->model('branch_rpt_model');
		$this->form_validation->set_rules($this->branch_rpt_model->getRules());
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == TRUE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();   
			$this->json_output();
			return;
		}

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		$this->ajxResp["data"] = "";
		$this->json_output();        
		
	}

	public function generateExcel($isPreview = 1) {
		$this->load->library("phpspreadsheet");
		
		$data = [
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];
		
		// print_r($data['selected_columns'][0]);die;
		

		$dataReport = $this->branch_rpt_model->queryComplete($data,"fst_branch_code",$data['rpt_layout']);

		$arrMerged = [];  //row,ttlColType(full,sum)
		if (isset($dataReport)) {
			if ($dataReport==[]) {
				print_r("Data Not Found!");
			}else {
				$repTitle = "";
		
				$spreadsheet = $this->phpspreadsheet->load();
				$sheet = $spreadsheet->getActiveSheet();								
				$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
				$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT;
				switch ($data['rpt_layout']){
					case "1":
						$repTitle = "LAPORAN DAFTAR CABANG";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 11;
						break;
					default:
						$repTitle = "LAPORAN DAFTAR CABANG";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 11;
						break;
				}	

				$spreadsheet->getProperties()->setCreator('QSystem - Indonesia')
				->setLastModifiedBy('Developer team')
				->setTitle($repTitle)
				->setSubject($repTitle)
				->setDescription($repTitle)
				->setKeywords('office 2007 openxml php')
				->setCategory('report file');
		
				$spreadsheet->getActiveSheet()->getPageSetup()
					->setOrientation($repOrientation);
				$spreadsheet->getActiveSheet()->getPageSetup()
					->setPaperSize($repPaperSize);
							
				// $spreadsheet->getActiveSheet()->getHeaderFooter()
				// ->setOddHeader('&C&HPlease treat this document as confidential!');
				
				$spreadsheet->getActiveSheet()->getHeaderFooter()
				->setOddFooter('&L&B' . $spreadsheet->getProperties()->getTitle() .date('d-m-Y H') . '-' . '&RPage &P of &N');
				$spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));
		
				$sheet->getPageSetup()->setFitToWidth(0);
				$sheet->getPageSetup()->setFitToHeight(0);
				$sheet->getPageMargins()->setTop(0.5);
				$sheet->getPageMargins()->setRight(0.5);
				$sheet->getPageMargins()->setLeft(0.5);
				$sheet->getPageMargins()->setBottom(0.5);
		
				$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				$spreadsheet->getDefaultStyle()->getFont()->setSize(24);
				$sheet->setCellValue("A1", $repTitle);
				
				//$sheet->mergeCells('A1:L1');                
				$arrMerged[] = [1,"FULL"];

				$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
				
				//ini contoh report layout 1 az yang sudah dibuat
                    $sheet->setCellValue("A3","Nou.");
                    $sheet->setCellValue("B3","Kode");
                    $sheet->setCellValue("C3","Nama Cabang");
                    $sheet->setCellValue("D3","Alamat");
                    $sheet->setCellValue("E3","Email");
                    $sheet->setCellValue("F3","PIC");
                    $sheet->setCellValue("G3","Token");
                    $sheet->setCellValue("H3","Conn string");
                    $sheet->setCellValue("I3","Blocked");

                    $sheet->getColumnDimension("A")->setAutoSize(false);
                    $sheet->getColumnDimension("B")->setAutoSize(true);
                    $sheet->getColumnDimension("C")->setAutoSize(true);
                    $sheet->getColumnDimension("D")->setAutoSize(true);
                    $sheet->getColumnDimension("E")->setAutoSize(true);
                    $sheet->getColumnDimension("F")->setAutoSize(true);
                    $sheet->getColumnDimension("G")->setAutoSize(true);
                    $sheet->getColumnDimension("H")->setAutoSize(true);
                    $sheet->getColumnDimension("I")->setAutoSize(true);

					$nou = 0;
					$cellRow = 4;
					$numOfRecs = count($dataReport);
					
					foreach($dataReport as $row){
						//$idx++;
                        $nou++;
                        $sheet->setCellValue("A".$cellRow,$nou);
                        $sheet->setCellValue("B".$cellRow,$row->fst_branch_code);
                        $sheet->setCellValue("C".$cellRow,$row->fst_branch_name);
                        $sheet->setCellValue("D".$cellRow,$row->fst_branch_address);
                        $sheet->setCellValue("E".$cellRow,$row->fst_email);
                        $sheet->setCellValue("F".$cellRow,$row->fst_pic);
                        $sheet->setCellValue("G".$cellRow,$row->fst_token);
						if( $row->fst_branchtemp_dbconnstring !=""){
							$row->fst_branchtemp_dbconnstring = get_decrypted($row->fst_branchtemp_dbconnstring);
							$sheet->setCellValue("H".$cellRow,$row->fst_branchtemp_dbconnstring);

						}else{
							$sheet->setCellValue("H".$cellRow,"");
						}
					
                        
						if ($row->fbl_is_blocked != 1) {
							$row->fbl_is_blocked = "No";
						} else if ($row->fbl_is_blocked = 1) {
							$row->fbl_is_blocked = "Yes";
						}
						$sheet->setCellValue("I".$cellRow,$row->fbl_is_blocked);                          
						$cellRow++;
					}
					

					$styleArray = [
						'borders' => [
							'allBorders' => [
								//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE
							],
						],
					];
					//$sheet->getStyle('A1:L'.$cellRow)->applyFromArray($styleArray);
					//$sheet->getStyle('A1:IV65536'.$cellRow)->applyFromArray($styleArray);
					$sheet->setShowGridlines(false);
					//BORDER
					$styleArray = [
						'borders' => [
							'allBorders' => [
								//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
							],
						],
					];
					$sheet->getStyle('A3:K'.$cellRow)->applyFromArray($styleArray);
		
					//FONT BOLD & Center
					$styleArray = [
						'font' => [
							'bold' => true,
						],
						'alignment' => [
							'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
						]
					];
					// $sheet->getStyle('A2')->applyFromArray($styleArray);
					$sheet->getStyle('A3:K3')->applyFromArray($styleArray);
					$sheet->getStyle('A3:A'.$cellRow)->applyFromArray($styleArray);

					//$styleArray = [
					//	'numberFormat'=> [
					//		'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
					//	]
					//];
					//$sheet->getStyle('H4:H'.$cellRow)->applyFromArray($styleArray);
					//$sheet->getStyle('J4:L'.$cellRow)->applyFromArray($styleArray);
					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY
						]
					];
					$sheet->getStyle('F4:F'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'font' => [
							'bold' => true,
							'size' => 24,
						],
					
					];
					$sheet->getStyle('A1')->applyFromArray($styleArray);

					$ttlSelectedCol = sizeof($data['selected_columns'][0]);
					$sumCol = $this->phpspreadsheet->getSumColPosition($this->layout_columns,$data['rpt_layout'],$data['selected_columns'][0]);
					$this->phpspreadsheet->cleanColumns($sheet,$fullColumn,$data['selected_columns'][0]);
					$this->phpspreadsheet->mergedData($sheet,$arrMerged,$ttlSelectedCol,$sumCol);

				if ($isPreview != 1) {
					$this->phpspreadsheet->save("Branch_List.xls" ,$spreadsheet);
					// $this->phpspreadsheet->savePDF();
				}else {
					//$this->phpspreadsheet->savePDF();
					$this->phpspreadsheet->saveHTMLvia($spreadsheet);    
				}
			}
		}else {
			print_r("Data Not Found !");
		}
    }
}