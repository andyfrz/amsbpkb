<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bpkbob extends MY_Controller
{
	public $menuName="bpkbob"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trbpkbob_model');
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
		$this->list['page_name'] = "BPKB Opening balance";
		$this->list['list_name'] = "BPKB Opening balance List";
		$this->list['addnew_ajax_url'] = site_url() . 'trx/bpkbob/add';
		//$this->list['report_url'] = site_url() . 'report/principalinvoices';
		$this->list['pKey'] = "finId";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'trx/bpkbob/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'trx/bpkbob/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'trx/bpkbob/edit/';
		$this->list['arrSearch'] = [
			'fstBpkbNo' => 'BPKB No.',
			'fstDealerCode' => 'Dealer',
            'fstCustomerName' => 'Customer'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Transaction', 'link' => '#', 'icon' => ''],
			['title' => 'BPKB Opening Balance', 'link' => NULL, 'icon' => ''],
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
			['title' => 'Action', 'width' => '15%', 'sortable' => false, 'className' => 'text-center',
			'render'=>"function(data,type,row){
				action = '<div style=\"font-size:16px\">';
				action += '<a class=\"btn-edit\" href=\"".site_url()."trx/bpkbob/edit/' + row.finId + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
				action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
				action += '<div>';
				return action;
			}"
		]
		];

        //$mdlSalesTrx = $this->parser->parse('template/mdlSalesTrx', $this->list, true);
		//$this->list['mdlSalesTrx'] = $mdlSalesTrx;
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = $this->parser->parse('template/standardList', $this->list, true);
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
		$data["title"] = $mode == "ADD" ? "Add Opening Balance" : "Update Opening Balance";
		$data["finId"] = $finId;

		$page_content = $this->parser->parse('pages/tr/bpkbob/form',$data,true);
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


	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('trbpkbob_model');
		$this->form_validation->set_rules($this->trbpkbob_model->getRules("ADD", ""));
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
			"fstBpkbNo" => $this->input->post("fstBpkbNo"),
			"fstBpkbStatus" => 'OB_CHECKIN',
			"fdtBpkbDate"=>dBDateFormat($this->input->post("fdtBpkbDate")),
			"fstDealerCode" => $this->input->post("fstDealerCode"),
            "fstCustomerName" => $this->input->post("fstCustomerName"),
			"fstNik" => $this->input->post("fstNik"),
			"fstNpwp" => $this->input->post("fstNpwp"),
            "fstBrandCode" => $this->input->post("fstBrandCode"),
			"finBrandTypeId" => $this->input->post("finBrandTypeId"),
			"fstColourCode" => $this->input->post("fstColourCode"),
            "fstEngineNo" => $this->input->post("fstEngineNo"),
			"fstChasisNo" => $this->input->post("fstChasisNo"),
			"finManufacturedYear" => $this->input->post("finManufacturedYear"),
            "finTrxId" => $this->input->post("finTrxId"),
			"finWarehouseId" => $this->input->post("finWarehouseId"),
			"fstInfo" => $this->input->post("fstInfo"),
			"fst_active" => 'A'
		];
		$this->db->trans_start();
		$insertId = $this->trbpkbob_model->insert($data);
		
		$log = [
			"fstBpkbNo" => $this->input->post("fstBpkbNo"),
			"fstTrxSource" => 'OB_CHECKIN',
			"fdtTrxDate"=> date("Y-m-d H:i:s"),
            "finTrxId" => $this->input->post("finTrxId"),
			"finWarehouseId" => $this->input->post("finWarehouseId"),
			"fstTrxInfo" => $this->input->post("fstInfo"),
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
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function ajx_edit_save()
	{
		parent::ajx_edit_save();
		$this->load->model('trbpkbob_model');
		$finId = $this->input->post('finId');
		$data = $this->trbpkbob_model->getDataById($finId);
		$bpkb = $data["bpkb"];
		if (!$bpkb) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $finId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->trbpkbob_model->getRules("EDIT", $finId));
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
			"fstBpkbStatus" => 'OB_CHECKIN',
			"fdtBpkbDate"=>dBDateFormat($this->input->post("fdtBpkbDate")),
			"fstDealerCode" => $this->input->post("fstDealerCode"),
            "fstCustomerName" => $this->input->post("fstCustomerName"),
			"fstNik" => $this->input->post("fstNik"),
			"fstNpwp" => $this->input->post("fstNpwp"),
            "fstBrandCode" => $this->input->post("fstBrandCode"),
			"finBrandTypeId" => $this->input->post("finBrandTypeId"),
			"fstColourCode" => $this->input->post("fstColourCode"),
            "fstEngineNo" => $this->input->post("fstEngineNo"),
			"fstChasisNo" => $this->input->post("fstChasisNo"),
			"finManufacturedYear" => $this->input->post("finManufacturedYear"),
            "finTrxId" => $this->input->post("finTrxId"),
			"finWarehouseId" => $this->input->post("finWarehouseId"),
			"fstInfo" => $this->input->post("fstInfo"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$this->trbpkbob_model->update($data);

		$log = [
			"fstBpkbNo" => $this->input->post("fstBpkbNo"),
			"fstTrxSource" => 'OB_CHECKIN',
			"fdtTrxDate"=> date("Y-m-d H:i:s"),
            "finTrxId" => $this->input->post("finTrxId"),
			"finWarehouseId" => $this->input->post("finWarehouseId"),
			"fstTrxInfo" => $this->input->post("fstInfo"),
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
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $finId;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
        $this->datatables->setTableName("(
            SELECT a.*,b.fstBrandName,c.fstColourName
            FROM trbpkb a LEFT JOIN tbbrandtypes b ON a.finBrandTypeId = b.finBrandTypeId
            LEFT JOIN tbcolours c ON a.fstColourCode = c.fstColourCode
            WHERE a.fstBpkbStatus ='OB_CHECKIN' ORDER BY a.fdtBpkbDate DESC
        ) a");

		$selectFields = "finId,fstBpkbNo,fdtBpkbDate,fstDealerCode,fstCustomerName,fstEngineNo,fstChasisNo,fstBrandName,fstColourName,'action' as action";
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
				<a class='btn-delete' href='#' data-id='" . $data["finId"] . "'><i class='fa fa-trash'></i></a>
			</div>";

			$arrDataFormated[] = $data;
		}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($finId)
	{
		$this->load->model('trbpkbob_model');
		$data = $this->trbpkbob_model->getDataById($finId);

		$this->json_output($data);
	}

	public function delete($id){
		parent::delete($id);
		$this->load->model('trbpkbob_model');
		$this->db->trans_start();
        $this->trbpkbob_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$this->load->model('trbpkbob_model');
		$result = $this->trbpkbob_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}

}
