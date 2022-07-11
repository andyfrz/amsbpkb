<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class salestrx extends MY_Controller
{

	public $menuName="salestrx"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
        $this->load->library('upload');
		$this->load->model('trsalestrx_model');
	}

	public function index()
	{
		$this->load->library('menus');
        $this->list['page_name']="Delete Tiket";
        $this->list['pKey']="id";
        $this->list['arrSearch']=[];
		
		$this->list['breadcrumbs']=[
			['title'=>'Home','link'=>'#','icon'=>"<i class='fa fa-dashboard'></i>"],
			['title'=>'Transaction','link'=>'#','icon'=>''],
			['title'=>'Salestrx','link'=> NULL ,'icon'=>''],
		];
		
		$this->list['columns']=[];
        $this->list["refreshToken"] = $this->session->userdata("refresh_token");
        $main_header = $this->parser->parse('inc/main_header',[],true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
        $page_content = $this->parser->parse('pages/tr/salestrx/form',$this->list,true);
        $main_footer = $this->parser->parse('inc/main_footer',[],true);
        $control_sidebar = "";
        $control_sidebar = null;
        $this->data['ACCESS_RIGHT']="A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR']= $main_sidebar;
        $this->data['PAGE_CONTENT']= $page_content;
        $this->data['MAIN_FOOTER']= $main_footer;        
        $this->parser->parse('template/main',$this->data);
	}

	public function lizt()
	{
		//parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "Salestrx";
		$this->list['list_name'] = "Salestrx List";
		//$this->list['addnew_ajax_url'] = site_url() . 'trx/salestrx/add';
		$this->list['report_url'] = site_url() . 'report/salestrx';
		$this->list['pKey'] = "finSalesTrxId";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'trx/salestrx/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'trx/salestrx/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'trx/salestrx/edit/';
		$this->list['arrSearch'] = [
			'fstSPKNo' => 'SPK No',
			'fstDealerCode' => 'Dealer Code'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Salestrx', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Id', 'width' => '10%', 'data' => 'finSalesTrxId'],
			['title' => 'Dealer Code', 'width' => '15%', 'data' => 'fstDealerCode'],
			['title' => 'SPK', 'width' => '10%', 'data' => 'fstSPKNo'],
			['title' => 'Date', 'width' => '15%', 'data' => 'fdtSalesDate'],
            ['title' => 'Customer', 'width' => '15%', 'data' => 'fstCustomerName'],
			['title' => 'Action', 'width' => '10%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
		];

        //$import_modal = $this->parser->parse('template/mdlImportSales', [], true);
		//$this->list['mdlImportSales'] = $import_modal;
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = $this->parser->parse('template/salestrxList', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = null;
		$this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
		$this->data['MAIN_HEADER'] = $main_header;
		$this->data['MAIN_SIDEBAR'] = $main_sidebar;
		$this->data['PAGE_CONTENT'] = $page_content;
		$this->data['MAIN_FOOTER'] = $main_footer;
		$this->parser->parse('template/main', $this->data);
	}


	public function fetch_list_data()
	{
		$this->load->library("datatables");
		$this->datatables->setTableName("trsalestrx");

		$selectFields = "finSalesTrxId,fstDealerCode,fstSPKNo,fdtSalesDate,fstCustomerName,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]    = "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["finSalesTrxId"] . "'><i class='fa fa-pencil'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fstSPKNo)
	{
		$this->load->model("trsalestrx_model");
		$data = $this->trsalestrx_model->getDataById($fstSPKNo);

		//if($data["fst_branchtemp_dbconnstring"] !=""){
		//	$data["fst_branchtemp_dbconnstring"] = $data["fst_branchtemp_dbconnstring"];
		//}

		//$this->load->library("datatables");		
		$this->json_output($data);
	}

	public function delete($fstSPKNo){
		parent::delete($fstSPKNo);
		$this->db->trans_start();
		$this->trsalestrx_model->delete($fstSPKNo);
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$result = $this->trsalestrx_model_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}

    public function load_import(){
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->vars['modal_title']='Import Sales';
			return $this->load->view('template/mdlImportSales',$this->vars);
		}else{
			redirect('trx/salestrx');
		}
	}

    function importOLD(){
		if(isset($_FILES["file"]["name"])){
			$this->upload->initialize($this->set_upload_options_excel('./uploads'));
			
			if ( ! $this->upload->do_upload('file')){
				$error = array('error' => $this->upload->display_errors());
				$this->vars['type']="alert-danger";
				$this->vars['message'] = $this->upload->display_errors();
			}else{
				$data = array('upload_data' => $this->upload->data());
				$filename = $this->upload->data('file_name');
				$path = './uploads/'.$filename;	
				
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

                $spreadsheet = $reader->load($path);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);	

                // array Count
                $arrayCount = count($allDataInSheet);
                $flag = 0;
                $createArray = array('NO', 'DEALER', 'CHL', 'DATE', 'REGIST', 'KONSUMEN', 'NIK');
                $makeArray = array('NO' => 'NO', 'DEALER' => 'fstDealerCode', 'CHL' => 'fstPaymentCode', 'DATE' => 'fdtSalesDate', 'REGIST' => 'fstSPKNo', 'KONSUMEN' => 'fstCustomerName', 'NIK' => 'fstNik');
                $SheetDataKey = array();
                foreach ($allDataInSheet as $dataInSheet) {
                    foreach ($dataInSheet as $key => $value) {
                        if (in_array(trim($value), $createArray)) {
                            $value = preg_replace('/\s+/', '', $value);
                            $SheetDataKey[trim($value)] = $key;
                        } 
                    }
                }

                $dataDiff = array_diff_key($makeArray, $SheetDataKey);
                if (empty($dataDiff)) {
                    $flag = 1;
                }
                // match excel sheet column
                if ($flag == 1) {
                    for ($i = 2; $i <= $arrayCount; $i++) {
                        $no = $SheetDataKey['NO'];
                        $dealer = $SheetDataKey['DEALER'];
                        $chl = $SheetDataKey['CHL'];
						$date = $SheetDataKey['DATE'];
						$regist = $SheetDataKey['REGIST'];
						$customer = $SheetDataKey['KONSUMEN'];
                        $nik = $SheetDataKey['NIK'];
 
                        $no = filter_var(trim($allDataInSheet[$i][$no]), FILTER_SANITIZE_STRING);
                        $dealer = filter_var(trim($allDataInSheet[$i][$dealer]), FILTER_SANITIZE_STRING);
                        $chl = filter_var(trim($allDataInSheet[$i][$chl]), FILTER_SANITIZE_STRING);
						$date = filter_var(trim($allDataInSheet[$i][$date]), FILTER_SANITIZE_STRING);
						$regist = filter_var(trim($allDataInSheet[$i][$regist]), FILTER_SANITIZE_STRING);
						$customer = filter_var(trim($allDataInSheet[$i][$customer]), FILTER_SANITIZE_STRING);
                        $nik = filter_var(trim($allDataInSheet[$i][$nik]), FILTER_SANITIZE_STRING);
						$date = strtotime($date);
						$date = date('Y-m-d',$date);
						//cek dulu sudah ada atau belum berdasarkan Nomor SPK/REGIST
						if($this->trsalestrx_model->is_present_sales($regist)){
							//sudah ada
							$info ="Nomor SPK/REGIST Sudah Ada!";
						}else{
							$query=$this->trsalestrx_model->add_new('trsalestrx',
								array('fstDealerCode' => $dealer,
								'fstPaymentCode' => $chl, 
								'fdtSalesDate' => $date, 
								'fstSPKNo' => $regist, 
								'fstCustomerName' => $customer, 
								'fstNik' => $nik,
                                'fdt_insert_datetime' => date("Y-m-d H:i:s"), 
                                'fin_insert_id' => $this->aauth->get_user_id(),
                                'fst_active' =>'A'
                                ));
							if($query){
								$info ="Tersimpan";
							}					
						}						
						
                        $fetchData[] = array('NO' => $no, 'DEALER' => $dealer, 'REGIST' => $regist, 'DATE' => $date, 'CUSTOMER' => $customer,'info' => $info);
                    }
					$this->vars['type']="alert-success";
					$this->vars['message']="Import Finish";
                    $this->vars['dataInfo'] = $fetchData;
                } else {
					$this->vars['type']="alert-danger";
					$this->vars['message']="Please import correct file, did not match excel sheet column";					
                }
                $this->lizt();
				//$this->vars['title']="Data dosen";
				//$this->vars['display_kampus']=$this->vars['dosen']=TRUE;
				//$this->vars['data']=$this->m_dosen->get_dosen();
				//$this->vars['content']='input/dosen';
				//$this->load->view('backend/index',$this->vars);	
			}
		}else{
			redirect('input/dosen');
		}
	}

    function import(){
		if(isset($_FILES["file"]["name"])){
			$this->upload->initialize($this->set_upload_options_excel('./uploads'));
			
			if ( ! $this->upload->do_upload('file')){
				$error = array('error' => $this->upload->display_errors());
				$this->vars['type']="alert-danger";
				$this->vars['message'] = $this->upload->display_errors();
			}else{
				$data = array('upload_data' => $this->upload->data());
				$filename = $this->upload->data('file_name');
				$path = './uploads/'.$filename;	
				
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

                $spreadsheet = $reader->load($path);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray();	

                // array Count
                $arrayCount = count($allDataInSheet);
                $flag = 1;
                $createArray = array('NO', 'DEALER', 'CHL', 'DATE', 'REGIST', 'KONSUMEN', 'NIK', 'NPWP', 'BAYAR', 'LEASING', 'TERMOFPAYMENT', 'CHANNEL', 'MERK', 'TYPE', 'WARNA', 'NORANGKA','NOMESIN', 'SALES', 'SURVEYOR', 'OFFROAD', 'BBN', 'KOMISI', 'HJUAL', 'DP', 'TOTALDISC', 'PROMOTION');
                /*$makeArray = array('NO' => 'NO', 'DEALER' => 'fstDealerCode', 'CHL' => 'CHL', 'DATE' => 'fdtSalesDate', 'REGIST' => 'fstSPKNo', 'KONSUMEN' => 'fstCustomerName', 'NIK' => 'fstNik',
                                    'NPWP' => 'fstNpwp', 'BAYAR' => 'fstPaymentCode', 'LEASING' => 'fstLeasingCode', 'TERMOFPAYMENT' => 'fmnNumOfInstallment', 'CHANNEL' => 'finManufacturedYear', 'MERK' => 'fstBrandCode', 'TYPE' => 'finBrandTypeId',
                                    'WARNA' => 'fstColourCode', 'NORANGKA' => 'fstEngineNo', 'NOMESIN' => 'fstChasisNo', 'SALES' => 'fstSalesName', 'SURVEYOR' => 'fstSurveyorName', 'OFFROAD' => 'fdcOffRoadPrice', 'BBN' => 'fdcBbn',
                                    'KOMISI' => 'fdcCommission', 'HJUAL' => 'fdcPriceList', 'DP' => 'fdcDownpayment', 'TOTALDISC' => 'fdcDiscount', 'PROMOTION' => 'fdcPromotion');
                */
                $makeArray = array('NO' => 'NO', 'DEALER' => 'fstDealerCode', 'CHL' => 'CHL', 'DATE' => 'fdtSalesDate', 'REGIST' => 'fstSPKNo', 'KONSUMEN' => 'fstCustomerName', 'NIK' => 'fstNik',
                'NPWP' => 'fstNpwp', 'BAYAR' => 'fstPaymentCode', 'LEASING' => 'fstLeasingCode', 'TERMOFPAYMENT' => 'fmnNumOfInstallment', 'CHANNEL' => 'finManufacturedYear', 'MERK' => 'fstBrandCode', 'TYPE' => 'finBrandTypeId',
                'WARNA' => 'fstColourCode', 'NORANGKA' => 'fstEngineNo', 'NOMESIN' => 'fstChasisNo', 'SALES' => 'fstSalesName', 'SURVEYOR' => 'fstSurveyorName', 'OFFROAD' => 'fdcOffRoadPrice', 'BBN' => 'fdcBbn',
                'KOMISI' => 'fdcCommission', 'HJUAL' => 'fdcPriceList', 'DP' => 'fdcDownpayment', 'TOTALDISC' => 'fdcDiscount', 'PROMOTION' => 'fdcPromotion');
                $SheetDataKey = array();
                foreach ($allDataInSheet as $dataInSheet) {
                    foreach ($dataInSheet as $key => $value) {
                        if (in_array(trim($value), $createArray)) {
                            $value = preg_replace('/\s+/', '', $value);
                            $SheetDataKey[trim($value)] = $key;
                        } 
                    }
                }

                $dataDiff = array_diff_key($makeArray, $SheetDataKey);
                if (empty($dataDiff)) {
                    $flag = 1;
                }
                // match excel sheet column
                if ($flag == 1) {
                    //for ($i = 2; $i <= $arrayCount; $i++) {
                    foreach($allDataInSheet as $x => $row) {
                        /*$no = $SheetDataKey['NO'];
                        $dealer = $SheetDataKey['DEALER'];
                        $chl = $SheetDataKey['CHL'];
						$date = $SheetDataKey['DATE'];
						$regist = $SheetDataKey['REGIST'];
						$customer = $SheetDataKey['KONSUMEN'];
                        $nik = $SheetDataKey['NIK'];
                        $npwp = $SheetDataKey['NPWP'];
						$bayar = $SheetDataKey['BAYAR'];
						$leasing = $SheetDataKey['LEASING'];
                        $top = $SheetDataKey['TERMOFPAYMENT'];
                        $channel = $SheetDataKey['CHANNEL'];
						$merk = $SheetDataKey['MERK'];
						$type = $SheetDataKey['TYPE'];
                        $warna = $SheetDataKey['WARNA'];
                        $engineNo = $SheetDataKey['NORANGKA'];
						$chasisNo = $SheetDataKey['NOMESIN'];
						$sales = $SheetDataKey['SALES'];
                        $surveyor = $SheetDataKey['SURVEYOR'];
                        $offroadPrice = $SheetDataKey['OFFROAD'];
						$bbn = $SheetDataKey['BBN'];
						$komisi = $SheetDataKey['KOMISI'];
                        $priceList = $SheetDataKey['HJUAL'];
                        $dp = $SheetDataKey['DP'];
						$totalDisc = $SheetDataKey['TOTALDISC'];
                        $promotion = $SheetDataKey['PROMOTION'];*/

                        if ($x == 0) {
                            continue;
                        }

                        $no = $row[0];
                        $dealer = $row[1];
                        $chl = $row[2];
						$date = $row[3];
						$regist = $row[4];
						$customer = $row[5];
                        $nik = $row[6];
                        $npwp = $row[7];
						$bayar = $row[8];
						$leasing = $row[9];
                        $top = $row[10];
                        $channel = $row[11];
						$merk = $row[12];
						$type = $row[13];
                        $warna = $row[14];
                        $engineNo = $row[15];
						$chasisNo = $row[16];
						$sales = $row[17];
                        $surveyor = $row[18];
                        $offroadPrice = $row[19];
						$bbn = $row[20];
						$komisi = $row[21];
                        $priceList = $row[22];
                        $dp = $row[22];
						$totalDisc = $row[23];
                        $promotion = $row[24];

                         
                        $no = filter_var(trim($no), FILTER_SANITIZE_STRING);
                        $dealer = filter_var(trim($dealer), FILTER_SANITIZE_STRING);
                        $chl = filter_var(trim($chl), FILTER_SANITIZE_STRING);
						$date = filter_var(trim($date), FILTER_SANITIZE_STRING);
						$regist = filter_var(trim($regist), FILTER_SANITIZE_STRING);
						$customer = filter_var(trim($customer), FILTER_SANITIZE_STRING);
                        //$nik = filter_var(trim($nik), FILTER_SANITIZE_STRING);
						$date = strtotime($date);
						$date = date('Y-m-d',$date);
                        $npwp = filter_var(trim($npwp), FILTER_SANITIZE_STRING);
						$bayar = filter_var(trim($bayar), FILTER_SANITIZE_STRING);
                        $leasing = filter_var(trim($leasing), FILTER_SANITIZE_STRING);
                        $top = filter_var(trim($top), FILTER_SANITIZE_STRING);
						$channel = filter_var(trim($channel), FILTER_SANITIZE_STRING);
                        $merk = filter_var(trim($merk), FILTER_SANITIZE_STRING);
                        $type = filter_var(trim($type), FILTER_SANITIZE_STRING);
						$warna = filter_var(trim($warna), FILTER_SANITIZE_STRING);
                        $engineNo = filter_var(trim($engineNo), FILTER_SANITIZE_STRING);
                        $chasisNo = filter_var(trim($chasisNo), FILTER_SANITIZE_STRING);
						$sales = filter_var(trim($sales), FILTER_SANITIZE_STRING);
                        $surveyor = filter_var(trim($surveyor), FILTER_SANITIZE_STRING);
                        $offroadPrice = filter_var(trim($offroadPrice), FILTER_SANITIZE_STRING);
						$bbn = filter_var(trim($bbn), FILTER_SANITIZE_STRING);
                        $komisi = filter_var(trim($komisi), FILTER_SANITIZE_STRING);
                        $priceList = filter_var(trim($priceList), FILTER_SANITIZE_STRING);
						$dp = filter_var(trim($dp), FILTER_SANITIZE_STRING);
                        $totalDisc = filter_var(trim($totalDisc), FILTER_SANITIZE_STRING);
                        $promotion = filter_var(trim($promotion), FILTER_SANITIZE_STRING);
 
                        /*$no = filter_var(trim($allDataInSheet[$i][$no]), FILTER_SANITIZE_STRING);
                        $dealer = filter_var(trim($allDataInSheet[$i][$dealer]), FILTER_SANITIZE_STRING);
                        $chl = filter_var(trim($allDataInSheet[$i][$chl]), FILTER_SANITIZE_STRING);
						$date = filter_var(trim($allDataInSheet[$i][$date]), FILTER_SANITIZE_STRING);
						$regist = filter_var(trim($allDataInSheet[$i][$regist]), FILTER_SANITIZE_STRING);
						$customer = filter_var(trim($allDataInSheet[$i][$customer]), FILTER_SANITIZE_STRING);
                        $nik = filter_var(trim($allDataInSheet[$i][$nik]), FILTER_SANITIZE_STRING);
						$date = strtotime($date);
						$date = date('Y-m-d',$date);
                        $npwp = filter_var(trim($allDataInSheet[$i][$npwp]), FILTER_SANITIZE_STRING);
						$bayar = filter_var(trim($allDataInSheet[$i][$bayar]), FILTER_SANITIZE_STRING);
                        $leasing = filter_var(trim($allDataInSheet[$i][$leasing]), FILTER_SANITIZE_STRING);
                        $top = filter_var(trim($allDataInSheet[$i][$top]), FILTER_SANITIZE_STRING);
						$channel = filter_var(trim($allDataInSheet[$i][$channel]), FILTER_SANITIZE_STRING);
                        $merk = filter_var(trim($allDataInSheet[$i][$merk]), FILTER_SANITIZE_STRING);
                        $type = filter_var(trim($allDataInSheet[$i][$type]), FILTER_SANITIZE_STRING);
						$warna = filter_var(trim($allDataInSheet[$i][$warna]), FILTER_SANITIZE_STRING);
                        $engineNo = filter_var(trim($allDataInSheet[$i][$engineNo]), FILTER_SANITIZE_STRING);
                        $chasisNo = filter_var(trim($allDataInSheet[$i][$chasisNo]), FILTER_SANITIZE_STRING);
						$sales = filter_var(trim($allDataInSheet[$i][$sales]), FILTER_SANITIZE_STRING);
                        $surveyor = filter_var(trim($allDataInSheet[$i][$surveyor]), FILTER_SANITIZE_STRING);
                        $offroadPrice = filter_var(trim($allDataInSheet[$i][$offroadPrice]), FILTER_SANITIZE_STRING);
						$bbn = filter_var(trim($allDataInSheet[$i][$bbn]), FILTER_SANITIZE_STRING);
                        $komisi = filter_var(trim($allDataInSheet[$i][$komisi]), FILTER_SANITIZE_STRING);
                        $priceList = filter_var(trim($allDataInSheet[$i][$priceList]), FILTER_SANITIZE_STRING);
						$dp = filter_var(trim($allDataInSheet[$i][$dp]), FILTER_SANITIZE_STRING);
                        $totalDisc = filter_var(trim($allDataInSheet[$i][$totalDisc]), FILTER_SANITIZE_STRING);
                        $promotion = filter_var(trim($allDataInSheet[$i][$promotion]), FILTER_SANITIZE_STRING);
                        */

						//cek dulu sudah ada atau belum berdasarkan Nomor SPK/REGIST
						if($this->trsalestrx_model->is_present_sales($regist)){
							//sudah ada
							$info ="Nomor SPK/REGIST Sudah Ada!";
						}else{
							$query=$this->trsalestrx_model->add_new('trsalestrx',
								array('fstDealerCode' => $dealer,
								'fdtSalesDate' => $date, 
								'fstSPKNo' => $regist, 
								'fstCustomerName' => $customer, 
								'fstNik' => $nik,
                                'fstNpwp' => $npwp,
                                'fstPaymentCode' => $bayar, 
                                'fstLeasingCode' => $leasing, 
                                'fmnNumOfInstallment' => $top, 
								'fstBrandCode' => $merk, 
								'finBrandTypeId' => $type,
                                'fstColourCode' => $warna,
                                'fstEngineNo' => $engineNo, 
                                'fstChasisNo' => $chasisNo,                 
                                'finManufacturedYear' => $channel, 
								'fstSalesName' => $sales, 
								'fstSurveyorName' => $surveyor,
                                'fdcOffRoadPrice' => $offroadPrice,
                                'fdcBbn' => $bbn, 
                                'fdcCommission' => $komisi, 
                                'fdcPriceList' => $priceList, 
								'fdcDownpayment' => $dp, 
								'fdcDiscount' => $totalDisc,
                                'fdcPromotion' => $promotion,
                                'fdt_insert_datetime' => date("Y-m-d H:i:s"), 
                                'fin_insert_id' => $this->aauth->get_user_id(),
                                'fst_active' =>'A'
                                ));
							if($query){
								$info ="Tersimpan";
							}					
						}						
						
                        $fetchData[] = array('NO' => $no, 'DEALER' => $dealer, 'REGIST' => $regist, 'DATE' => $date, 'CUSTOMER' => $customer,'info' => $info);
                    }
					$this->vars['type']="alert-success";
					$this->vars['message']="Import Finish";
                    $this->vars['dataInfo'] = $fetchData;
                } else {
					$this->vars['type']="alert-danger";
					$this->vars['message']="Please import correct file, did not match excel sheet column";					
                }
                //$this->lizt();
				//$this->vars['title']="Data dosen";
				//$this->vars['display_kampus']=$this->vars['dosen']=TRUE;
				//$this->vars['data']=$this->m_dosen->get_dosen();
				//$this->vars['content']='input/dosen';
				//$this->load->view('backend/index',$this->vars);
                redirect('trx/salestrx');	
			}
		}else{
			redirect('trx/salestrx');
		}
	}
	private function set_upload_options_excel($file_path){   
		//  upload an image options
		$config = array();
		$config['upload_path'] = $file_path;
		$config['allowed_types'] = 'csv|xlsx|xls';
		$config['max_size']      = '15360';
		$config['overwrite']     = TRUE;
		$config['encrypt_name'] = TRUE;
		return $config;
	}

    function download_salestrx(){
		$startDateString = date("Ymd", strtotime($this->input->post("fdtSalesDate")));
		$endDateString = date("Ymd", strtotime($this->input->post("fdtSalesDate2")));
		$date = date("ydm");
		$time = date("Hi");
		$token = md5($date.'T'.$time);
		$data = array(
			'startDateString' => $startDateString,
			'endDateString' => $endDateString,
			'token' => $token ,
			'request_time' => $time
		);
		
		$endpoint = "http://36.94.119.139:5100/api/bpkb/getAccountDataList";
		$url = $endpoint . '?' . http_build_query($data);

		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$resp = curl_exec($curl);
		curl_close($curl);

        $this->load->model('trsalestrx_model');;
		//$data = file_get_contents($resp);
		$this->db->trans_start();
		$details = json_decode($resp, true);
		foreach ($details as $detail=>$salestrx) {
			$info = '';
			if($this->trsalestrx_model->is_present_sales($salestrx['SalesOrderNo'])){
				$info ="SPK No already exists!";
				$fetchData[] = array('SPK No' => $salestrx['SalesOrderNo'], 'Sales Date' => $salestrx['AccountDate'], 'Customer' => $salestrx['CustName'], 'info' => $info);
			}else{
				$data = [
					'fstDealerCode' => $salestrx['DealerCode'],
					'fdtSalesDate' => $salestrx['AccountDate'], 
					'fstSPKNo' => $salestrx['SalesOrderNo'],
					'fstCustomerName' => $salestrx['CustName'],
					'fstNik' => $salestrx['IDNo'],
					'fstNpwp' => $salestrx['IDNo'],
					'fstPaymentCode' => '-',
					'fstLeasingCode' => '-',
					'fmnNumOfInstallment' => $salestrx['NumOfInstallment'],
					'fstBrandCode' => $salestrx['BranchCode'],
					'finBrandTypeId' => $salestrx['TMTypeCode'],
					'fstColourCode' => $salestrx['ColourCode'],
					'fstEngineNo' => $salestrx['EngineNo'],
					'fstChasisNo' => $salestrx['ChassisNo'],   
					'finManufacturedYear' => $salestrx['Year'],
					'fstSalesName' => '-',
					'fstSurveyorName' => $salestrx['SurveyorName'],
					'fdcOffRoadPrice' => $salestrx['Price'],
					'fdcBbn' => '0',
					'fdcCommission' =>'0',
					'fdcPriceList' => $salestrx['Price'],
					'fdcDownpayment' => $salestrx['DownPayment'],
					'fdcDiscount' => '0',
					'fdcPromotion' => '0',
					'fin_insert_id' => $this->aauth->get_user_id(),
					'fst_active' =>'A'
				];
				$this->trsalestrx_model->insert($data);
			}
			
			$dbError  = $this->db->error();
			if ($dbError["code"] != 0) {
				$this->ajxResp["status"] = "DB_FAILED";
				$this->ajxResp["message"] = "Insert Detail Failed";
				$this->ajxResp["data"] = $this->db->error();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}

		}
		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		if($info){
			$this->ajxResp["message"] = $fetchData;
		}else{
			$this->ajxResp["message"] = "Insert Success";
		}
		
		$this->ajxResp["data"]["insert_id"] = 'admin';
		$this->json_output();
        redirect('trx/salestrx');	
	}
	
	function download_colours(){
		$date = date("ydm");
		$time = date("Hi");
		$token = md5($date.'T'.$time);
		/*$data = array(
			'startDateString' => '20220601',
			'endDateString' => '20220610',
			'token' => $token ,
			'request_time' => $time
		);*/
		$data = array(
			'token' => $token ,
			'request_time' => $time
		);
		
		$endpoint = "http://36.94.119.139:5100/api/bpkb/getColourDataList";
		
		//$endpoint = "http://36.94.119.139:5100/api/bpkb/getAccountDataList";
		$url = $endpoint . '?' . http_build_query($data);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$resp = curl_exec($curl);
		curl_close($curl);

        $this->load->model('Mscolours_model');;
		//$data = file_get_contents($resp);
		$this->db->trans_start();
		$details = json_decode($resp, true);
		foreach ($details as $detail=>$item) {
			//$fstColourCode = $this->Mscolours_model->getDataById($item['ColourCode']);
			//if( $item['ColourCode'] == $fstColourCode ){
				$data = [
					"fstColourCode" => $item['ColourCode'],
					"fstColourName" => $item['ColourName'],
					"fst_active" =>'A'
				];
			//};
			
			$this->Mscolours_model->insert($data);
			$dbError  = $this->db->error();
			if ($dbError["code"] != 0) {
				$this->ajxResp["status"] = "DB_FAILED";
				$this->ajxResp["message"] = "Insert Detail Failed";
				$this->ajxResp["data"] = $this->db->error();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}
		}
		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = $details;
		$this->ajxResp["data"]["insert_id"] = 'admin';
		$this->json_output();
        //redirect('trx/salestrx');	
	}

	function download_dealers(){
		$date = date("ydm");
		$time = date("Hi");
		$token = md5($date.'T'.$time);
		/*$data = array(
			'startDateString' => '20220601',
			'endDateString' => '20220610',
			'token' => $token ,
			'request_time' => $time
		);*/
		$data = array(
			'token' => $token ,
			'request_time' => $time
		);
		
		$endpoint = "http://36.94.119.139:5100/api/bpkb/getDealerDatalist";
		
		//$endpoint = "http://36.94.119.139:5100/api/bpkb/getAccountDataList";
		$url = $endpoint . '?' . http_build_query($data);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$resp = curl_exec($curl);
		curl_close($curl);

        $this->load->model('Msdealers_model');;
		//$data = file_get_contents($resp);
		$this->db->trans_start();
		$details = json_decode($resp, true);
		foreach ($details as $detail=>$item) {
			//$fstColourCode = $this->Mscolours_model->getDataById($item['ColourCode']);
			//if( $item['ColourCode'] == $fstColourCode ){
				$data = [
					"fstDealerCode" => $item['DealerCode'],
					"fstDealerName" => $item['DealerName'],
					"fstPersonInCharge" => $item['PICGeneral'],
					"fstPhoneNo" => $item['Phone1'],
					"fstAddress" => $item['Address1'],
					"fstEmail" => $item['Email'],
					"fst_active" =>'A'
				];
			//};
			
			$this->Msdealers_model->insert($data);
			$dbError  = $this->db->error();
			if ($dbError["code"] != 0) {
				$this->ajxResp["status"] = "DB_FAILED";
				$this->ajxResp["message"] = "Insert Detail Failed";
				$this->ajxResp["data"] = $this->db->error();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}
		}
		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = $details;
		$this->ajxResp["data"]["insert_id"] = 'admin';
		$this->json_output();
        //redirect('trx/salestrx');	
	}


}
