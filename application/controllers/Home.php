<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('dashboard_model');
	}
	
	public function index(){				
		$this->load->library("menus");
		
		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$this->list["title"] = "Dashboard";
		//$this->list["ttlDownload"] = formatNumber($this->dashboard_model->getTtlDownload());
		//$this->list["ttlSuccess"] = formatNumber($this->dashboard_model->getTtlSuccess());
		//$this->list["ttlOnProgress"] = formatNumber($this->dashboard_model->getTtlOnProgress());
		//$this->list["ttlFailed"] = formatNumber($this->dashboard_model->getTtlFailed());
		$this->list["downloads"] = "";
		//$this->list["refreshToken"] = $this->session->userdata("refresh_token");

		$page_content = $this->parser->parse('pages/dashboard/dashboard', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main',$this->data);
	}

	public function history($fst_branch_code){				
		$this->load->library("menus");
		
		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$this->list["title"] = "History download data";
		$this->list["branch"] = $fst_branch_code;
		$this->list["downloads"] = $this->dashboard_model->getHistoryDownload($fst_branch_code);

		$page_content = $this->parser->parse('pages/tr/history', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main',$this->data);
	}

	public function generateExcel($isPreview = 1) {
		$this->load->library("phpspreadsheet");
		
		$data = [
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];
		
		// print_r($data['selected_columns'][0]);die;
		

		$dataReport = $this->trlog_branch_model->queryComplete($data,"fst_branch_code",$data['rpt_layout']);

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
						$repTitle = "DOWNLOAD REPORT";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 9;
						break;
					default:
						$repTitle = "DOWNLOAD REPORT";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 9;
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
				//if  ($data['rpt_layout'] == 1){
                    $sheet->setCellValue("A3","Nou.");
                    $sheet->setCellValue("B3","Kode");
                    $sheet->setCellValue("C3","Nama Cabang");
                    $sheet->setCellValue("D3","Start Date");
                    $sheet->setCellValue("E3","End Date");
                    $sheet->setCellValue("F3","Data range startdate");
                    $sheet->setCellValue("G3","Data range enddate");
                    $sheet->setCellValue("H3","Status");
                    $sheet->setCellValue("I3","Info");

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
                        $sheet->setCellValue("D".$cellRow,$row->fdt_start_datetime);
                        $sheet->setCellValue("E".$cellRow,$row->fdt_end_datetime);
                        $sheet->setCellValue("F".$cellRow,$row->fdt_datarange_start_date);
                        $sheet->setCellValue("G".$cellRow,$row->fdt_datarange_end_date);
                        $sheet->setCellValue("H".$cellRow,$row->fst_status);
						$sheet->setCellValue("I".$cellRow,$row->fst_info);                          
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
					$sheet->getStyle('A3:I'.$cellRow)->applyFromArray($styleArray);
		
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
					$sheet->getStyle('A3:I3')->applyFromArray($styleArray);
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

					//$ttlSelectedCol = 9;
					//$sumCol = $this->phpspreadsheet->getSumColPosition($this->layout_columns,$data['rpt_layout'],$data['selected_columns'][0]);
					//$this->phpspreadsheet->cleanColumns($sheet,$fullColumn,$data['selected_columns'][0]);
					//$this->phpspreadsheet->mergedData($sheet,$arrMerged,9,9);

				//} //end if layout 1

				if ($isPreview != 1) {
					$this->phpspreadsheet->save("Download_report.xls" ,$spreadsheet);
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