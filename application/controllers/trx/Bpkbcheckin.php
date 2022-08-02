<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bpkbcheckin extends MY_Controller
{
	public $menuName="bpkbcheckin"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trbpkbcheckin_model');
		$this->load->model('trlog_bpkb_model');
	}

	public function index()
	{
		parent::index();
		$this->lizt();
	}

	public function lizt()
	{
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "BPKB Checkin";
		$this->list['list_name'] = "BPKB Checkin List";
		$this->list['addnew_ajax_url'] = site_url() . 'trx/bpkbcheckin/add';
		//$this->list['report_url'] = site_url() . 'report/principalinvoices';
		$this->list['pKey'] = "finId";
        $this->list['trx_key'] = "finSalesTrxId";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'trx/bpkbcheckin/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'trx/bpkbcheckin/delete/';
		$this->list['ajx_edit_checkin'] = site_url() . 'trx/bpkbcheckin/ajx_edit_checkin/';
        $this->list['checkin_ajax_url'] = site_url() . 'trx/bpkbcheckin/checkin_trx/';
        $this->list['ischeckin'] = site_url() . 'trx/bpkbcheckin/ischeckin/';
		$this->list['isnotcheckin'] = site_url() . 'trx/bpkbcheckin/isnotcheckin/';
		$this->list['arrSearch'] = [
			'fstBpkbNo' => 'BPKB No.',
			'fstDealerCode' => 'Dealer',
            'fstCustomerName' => 'Customer',
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Transaction', 'link' => '#', 'icon' => ''],
			['title' => 'BPKB Checkin', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
            ['title' => 'ID', 'width' => '0%','visible'=>'false', 'data' => 'finId'],
			['title' => 'BPKB No', 'width' => '10%', 'data' => 'fstBpkbNo'],
			['title' => 'BPKB Date', 'width' => '10%', 'data' => 'fdtBpkbDate'],
            ['title' => 'Dealer', 'width' => '10%', 'data' => 'fstDealerCode'],
			['title' => 'Customer', 'width' => '10%', 'data' => 'fstCustomerName'],
            ['title' => 'Engine No', 'width' => '10%', 'data' => 'fstEngineNo'],
            ['title' => 'Chasis No', 'width' => '10%', 'data' => 'fstChasisNo'],
            ['title' => 'Brand', 'width' => '10%', 'data' => 'fstBrandName'],
            ['title' => 'Colour', 'width' => '5%', 'data' => 'fstColourName'],
			['title' => 'Info', 'width' => '0%','visible'=>'false', 'data' => 'fstInfo'],
			['title' => 'Action', 'width' => '10%', 'sortable' => false, 'className' => 'text-center',
                'render'=>"function(data,type,row){
                    action = '<div style=\"font-size:16px\">';
                    action += '<a class=\"btn-edit\" href=\"#\" data-id=\"\"><i class=\"fa fa-pencil-square-o\"></i></a>&nbsp;';
                    action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
                    action += '<div>';
                    return action;
                }"
		    ]
		];

        $mdlPrint = $this->parser->parse('template/mdlPrint', $this->list, true);
		$this->list['mdlPrint'] = $mdlPrint;
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = $this->parser->parse('pages/tr/bpkbcheckin/list', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = null;
		$this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
		$this->data['MAIN_HEADER'] = $main_header;
		$this->data['MAIN_SIDEBAR'] = $main_sidebar;
		$this->data['PAGE_CONTENT'] = $page_content;
		$this->data['MAIN_FOOTER'] = $main_footer;
		$this->parser->parse('template/main', $this->data);
	}

	public function openForm($mode="ADD",$finId=0){
        $this->load->library("menus");
		$this->load->model("msdealers_model");
		$this->load->model("msbrands_model");
		$this->load->model("mscolours_model");
		$this->load->model("msbrandtypes_model");
		$this->load->model("msbpkbtrxs_model");
		$this->load->model("msbpkbwarehouse_model");
		
		if($this->input->post("submit") != "" ){
			$this->add_save();
		}

        $main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add BPKB Checkin" : "Update BPKB Checkin";
		$data["finId"] = $finId;

		$page_content = $this->parser->parse('pages/tr/bpkbcheckin/form',$data,true);
		$main_footer = $this->parser->parse('inc/main_footer',[],true);
			
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main',$this->data);
    }

	public function add()
	{
		parent::add();
		$this->openForm("ADD", 0);
	}

	public function edit($finId){
        $this->openForm("EDIT",$finId);
    }


	public function checkin_trx()
	{
		//parent::checkin_trx();
		$this->load->model('trbpkbcheckin_model');
		$this->form_validation->set_rules($this->trbpkbcheckin_model->getRules("ADD", ""));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}

		$this->load->model('trsalestrx_model');
		$this->load->model('msbpkbwarehouse_model');
		$datawarehouse = $this->msbpkbwarehouse_model->getMainWarehouse();
		$warehouse = $datawarehouse["bpkbwarehouse"];
		$finSalesTrxId = $this->input->post('finSalesTrxId');
		$data = $this->trsalestrx_model->getDataById($finSalesTrxId);
		$salestrx = $data["salestrx"];
		if (!$salestrx) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Sales Trx id $finSalesTrxId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$data = [
			"fstBpkbNo" => $this->input->post("fstBpkbNo"),
			"fstBpkbStatus" => 'CHECKIN',
			"finSalesTrxId" => $finSalesTrxId,
			"fdtBpkbDate"=>dBDateFormat($this->input->post("fdtBpkbDate")),
			"fstDealerCode" => $salestrx->fstDealerCode,
            "fstCustomerName" => $salestrx->fstCustomerName,
			"fstNik" => $salestrx->fstNik,
			"fstNpwp" => $salestrx->fstNpwp,
            "fstBrandCode" => $salestrx->fstBrandCode,
			"finBrandTypeId" => $salestrx->finBrandTypeId,
			"fstColourCode" => $salestrx->fstColourCode,
            "fstEngineNo" => $salestrx->fstEngineNo,
			"fstChasisNo" => $salestrx->fstChasisNo,
			"finManufacturedYear" => $salestrx->finManufacturedYear,
			"finTrxId" => '4',
			"finWarehouseId" => $warehouse->finWarehouseId,
			"fstInfo" => $this->input->post("fstInfo"),
			"fst_active" => 'A'
		];
		if ($salestrx->fstLeasingCode !='' || $salestrx->fstLeasingCode != null || $salestrx->fstLeasingCode !='FMF'){
			$data["finTrxId"] = '4';
		}else if ($salestrx->fstLeasingCode =='FMF'){
			$data["finTrxId"] = '3';
		}else if ($salestrx->fstLeasingCode =='' || $salestrx->fstLeasingCode == null){
			$data["finTrxId"] = '1';
		}

		$this->db->trans_start();
		$insertId = $this->trbpkbcheckin_model->insert($data);
		
		$log = [
			"fstBpkbNo" => $this->input->post("fstBpkbNo"),
			"fstTrxSource" => 'CHECKIN',
			"fdtTrxDate"=> date("Y-m-d H:i:s"),
            "finTrxId" => $finSalesTrxId,
			"finWarehouseId" => $warehouse->finWarehouseId,
			"fstTrxInfo" => $this->input->post("fstInfo"),
			"fdbIn"=>1,
			"fdbOut"=>0,
			"fst_active" => 'A'
		];
		$this->trlog_bpkb_model->insert($log);

		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Checkin Success !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function ajx_edit_checkin()
	{
		parent::ajx_edit_save();
		$this->load->model('trbpkbcheckin_model');
		$finId = $this->input->post('finId');
		$data = $this->trbpkbcheckin_model->getDataById($finId);
		$bpkb = $data["bpkb"];
		if (!$bpkb) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $finId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->trbpkbcheckin_model->getRules("EDIT", $finId));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}

		$data = [
			"finId" => $finId,
			"fstBpkbNo" => $this->input->post("fstBpkbNo"),
			"fdtBpkbDate"=>dBDateFormat($this->input->post("fdtBpkbDate")),
			"fstInfo" => $this->input->post("fstInfo"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$this->trbpkbcheckin_model->update($data);

		/*$log = [
			"fstBpkbNo" => $this->input->post("fstBpkbNo"),
			"fstTrxSource" => 'CHECKIN',
			"fdtTrxDate"=> date("Y-m-d H:i:s"),
            "finTrxId" => $this->input->post("finTrxId"),
			"finWarehouseId" => $this->input->post("finWarehouseId"),
			"fstTrxInfo" => $this->input->post("fstInfo"),
			"fst_active" => 'A'
		];
		$this->trlog_bpkb_model->insert($log);*/
		
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}
		$this->trbpkbcheckin_model->updateLogBpkb($finId);
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Update Success !";
		$this->ajxResp["data"]["insert_id"] = $finId;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$user = $this->aauth->user();
        $activeDealer = $user->fstDealerCode;
		$this->load->library("datatables");
		if ($activeDealer !=""){
			$this->datatables->setTableName("(
				SELECT a.*,b.fstBrandName,c.fstColourName
				FROM trbpkb a LEFT JOIN tbbrandtypes b ON a.finBrandTypeId = b.finBrandTypeId
				LEFT JOIN tbcolours c ON a.fstColourCode = c.fstColourCode
				WHERE a.fstBpkbStatus ='CHECKIN' AND a.fstDealerCode ='$activeDealer' ORDER BY a.fdtBpkbDate DESC
			) a");
		}else{
			$this->datatables->setTableName("(
				SELECT a.*,b.fstBrandName,c.fstColourName
				FROM trbpkb a LEFT JOIN tbbrandtypes b ON a.finBrandTypeId = b.finBrandTypeId
				LEFT JOIN tbcolours c ON a.fstColourCode = c.fstColourCode
				WHERE a.fstBpkbStatus ='CHECKIN' ORDER BY a.fdtBpkbDate DESC
			) a");
		}


		$selectFields = "finId,fstBpkbNo,fdtBpkbDate,fstDealerCode,fstCustomerName,fstEngineNo,fstChasisNo,fstBrandName,fstColourName,fstInfo,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$fdtBpkbDate = strtotime($data["fdtBpkbDate"]);
			$data["fdtBpkbDate"] = date("d-M-Y",$fdtBpkbDate);

			//action
			$data["action"]	= "<div style='font-size:16px'>
				<a class='btn-edit' href='#' data-id='" . $data["finId"] . "'><i class='fa fa-pencil-square-o'></i></a>
				<a class='btn-delete' href='#' data-id='" . $data["finId"] . "'><i class='fa fa-trash'></i></a>
			</div>";

			$arrDataFormated[] = $data;
		}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($finId)
	{
		$this->load->model('trbpkbcheckin_model');
		$data = $this->trbpkbcheckin_model->getDataById($finId);

		$this->json_output($data);
	}

	public function delete($id){
		parent::delete($id);
		$this->load->model('trbpkbcheckin_model');
		$this->db->trans_start();
        $this->trbpkbcheckin_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$this->load->model('trbpkbcheckin_model');
		$result = $this->trbpkbcheckin_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}
	public function ischeckin($finSalesTrxId)
	{
		$this->load->model('trbpkbcheckin_model');
		$data = $this->trbpkbcheckin_model->getTrxId($finSalesTrxId);
		$trxbpkb = $data["trxbpkb"];
		if ($trxbpkb) {
            $this->ajxResp["status"] = "READY";
			$this->ajxResp["message"] = "BPKB Already Checkin";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}else{
            $this->ajxResp["status"] = "NOT READY";
			$this->ajxResp["message"] = "";
			$this->ajxResp["data"] = [];
			$this->json_output();
        }
	}
	public function isnotcheckin($finId)
	{
		$this->load->model('trbpkbcheckin_model');
		$data = $this->trbpkbcheckin_model->getCheckin($finId);
		$bpkbcheckin = $data["bpkbcheckin"];
		if ($bpkbcheckin) {
            $this->ajxResp["status"] = "READY";
			$this->ajxResp["message"] = "";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}else{
            $this->ajxResp["status"] = "NOT READY";
			$this->ajxResp["message"] = "BPKB Status <> CHECKIN !!!";
			$this->ajxResp["data"] = [];
			$this->json_output();
        }
	}
    public function ajxSalesTrxData(){

        $customer_name = $this->input->post("fstCustomerName");
        $nik = $this->input->post("fstNik");
        $spk_no = $this->input->post("fstSPKNo");
        $brand_name = $this->input->post("fstBrandName");
        $engine_no = $this->input->post("fstEngineNo");
        $chasis_no = $this->input->post("fstChasisNo");
        /*if (isset($data['fstCustomerName'])) { $customer_name = $data['fstCustomerName'];}
        if (isset($data['fstNik'])) { $nik = $data['fstNik'];}
        if (isset($data['fstSPKNo'])) { $spk_no = $data['fstSPKNo'];}
        if (isset($data['fstBrandName'])) { $brand_name = $data['fstBrandName'];}
        if (isset($data['fstEngineNo'])) { $engine_no = $data['fstEngineNo'];}
        if (isset($data['fstChasisNo'])) { $chasis_no = $data['fstChasisNo'];}*/

        $swhere = "";
        //$sorderby = "";
        if ($customer_name != "") {
            //$swhere .= " and a.fstCustomerName = " . $this->db->escape($customer_name);
            $swhere .= " AND a.fstCustomerName LIKE '%" . $customer_name ."%'";
        }
        if ($nik != "") {
            $swhere .= " and a.fstNik = " . $this->db->escape($nik);
        }
        if ($spk_no != "") {
            $swhere .= " and a.fstSPKNo = " . $this->db->escape($spk_no);
        }
        if ($brand_name != "") {
            $swhere .= " and b.fstBrandName = " . $this->db->escape($brand_name);
        }
        if ($engine_no != "") {
            $swhere .= " and a.fstEngineNo = " . $this->db->escape($engine_no);
        }
        if ($chasis_no != "") {
            $swhere .= " and a.fstChasisNo = " . $this->db->escape($chasis_no);
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);

            $ssql = "SELECT a.*,b.fstBrandCode,b.fstBrandName FROM trsalestrx a LEFT JOIN tbbrandtypes b ON a.finBrandTypeId = b.finBrandTypeId  $swhere GROUP BY a.finSalesTrxId";
            $qr = $this->db->query($ssql);
            //echo $this->db->last_query();
            //die();
            $rs = $qr->result();
    
            $result = [
                "status"=>"SUCCESS",
                "data"=>$rs
            ];
    
            
            header('Content-Type: application/json');
            echo json_encode($result);

        }


	}

}
