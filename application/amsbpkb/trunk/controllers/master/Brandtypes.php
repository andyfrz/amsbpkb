<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brandtypes extends MY_Controller
{

	public $menuName="brandtypes"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('msbrandtypes_model');
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
		$this->list['page_name'] = "Brand type";
		$this->list['list_name'] = "Brand type List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/brandtypes/add';
		$this->list['report_url'] = site_url() . 'report/Brandtypes';
		$this->list['pKey'] = "finBrandTypeId";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/brandtypes/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/brandtypes/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/brandtypes/edit/';
		$this->list['arrSearch'] = [
			'finBrandTypeId' => 'Brand type ID',
            'fstBrandCode' => 'Brand Code',
			'fstBrandName' => 'Brand Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Brand type', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
            ['title' => 'Type ID', 'width' => '5%', 'data' => 'finBrandTypeId'],
			['title' => 'Brand Code', 'width' => '10%', 'data' => 'fstBrandCode'],
			['title' => 'Brand Name', 'width' => '15%', 'data' => 'fstBrandName'],
            ['title' => 'Engine Prefix', 'width' => '10%', 'data' => 'fstEnginePrefix'],
            ['title' => 'Chassis Prefix', 'width' => '10%', 'data' => 'fstChassisPrefix'],
			['title' => 'Genesys Code', 'width' => '10%', 'data' => 'fstGenesysBrandTypeCode'],
			['title' => 'Action', 'width' => '10%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
		];
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

	private function openForm($mode = "ADD", $finBrandTypeId = 0)
	{
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Brand" : "Update Brand type";
		$data["finBrandTypeId"] = $finBrandTypeId;

		$page_content = $this->parser->parse('pages/master/brandtypes/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}

	public function add()
	{
		parent::add();
		$this->openForm("ADD", 0);
	}

	public function edit($finBrandTypeId)
	{
		parent::edit($finBrandTypeId);
		$this->openForm("EDIT", $finBrandTypeId);
	}

	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('msbrandtypes_model');
		
		$this->form_validation->set_rules($this->msbrandtypes_model->getRules("ADD", 0));
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
			"fstBrandCode" => $this->input->post("fstBrandCode"),
			"fstBrandName" => $this->input->post("fstBrandName"),
            "fstEnginePrefix" => $this->input->post("fstEnginePrefix"),
			"fstChassisPrefix" => $this->input->post("fstChassisPrefix"),
			"fstGenesysBrandTypeCode" => $this->input->post("fstGenesysBrandTypeCode"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->msbrandtypes_model->insert($data);
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
		$this->load->model('msbrandtypes_model');
		$finBrandTypeId = $this->input->post("finBrandTypeId");
		$data = $this->msbrandtypes_model->getDataById($finBrandTypeId);
		$brandtype = $data["brandtypes"];
		if (!$brandtype) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $finBrandTypeId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->msbrandtypes_model->getRules("EDIT", $finBrandTypeId));
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
            "finBrandTypeId" => $finBrandTypeId,
			"fstBrandCode" => $this->input->post("fstBrandCode"),
			"fstBrandName" => $this->input->post("fstBrandName"),
            "fstEnginePrefix" => $this->input->post("fstEnginePrefix"),
			"fstChassisPrefix" => $this->input->post("fstChassisPrefix"),
			"fstGenesysBrandTypeCode" => $this->input->post("fstGenesysBrandTypeCode"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();

		$this->msbrandtypes_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $finBrandTypeId;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
		$this->datatables->setTableName("tbbrandtypes");

		$selectFields = "finBrandTypeId,fstBrandCode,fstBrandName,fstEnginePrefix,fstChassisPrefix,fstGenesysBrandTypeCode,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["finBrandTypeId"] . "'><i class='fa fa-pencil'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($finBrandTypeId)
	{
		$this->load->model("msbrandtypes_model");
		$data = $this->msbrandtypes_model->getDataById($finBrandTypeId);

		//if($data["fst_Brandtemp_dbconnstring"] !=""){
		//	$data["fst_Brandtemp_dbconnstring"] = $data["fst_Brandtemp_dbconnstring"];
		//}

		//$this->load->library("datatables");		
		$this->json_output($data);
	}

	public function delete($finBrandTypeId){
		parent::delete($finBrandTypeId);
		$this->db->trans_start();
		$this->msbrandtypes_model->delete($finBrandTypeId);
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$result = $this->msbrandtypes_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}
}
