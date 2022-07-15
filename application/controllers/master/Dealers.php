<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealers extends MY_Controller
{

	public $menuName="dealer"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('msdealers_model');
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
		$this->list['page_name'] = "Dealer";
		$this->list['list_name'] = "Dealer List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/dealers/add';
		$this->list['report_url'] = site_url() . 'report/dealers';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/dealers/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/dealers/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/dealers/edit/';
		$this->list['arrSearch'] = [
			'fstDealerCode' => 'Dealer Code',
			'fstDealerName' => 'Dealer Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Dealer', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Dealer Code', 'width' => '10%', 'data' => 'fstDealerCode'],
			['title' => 'Dealer Name', 'width' => '15%', 'data' => 'fstDealerName'],
			['title' => 'PIC', 'width' => '10%', 'data' => 'fstPersonInCharge'],
			['title' => 'Genesys Code', 'width' => '15%', 'data' => 'fstGenesysDealerCode'],
            ['title' => 'Address', 'width' => '15%', 'data' => 'fstAddress'],
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

	private function openForm($mode = "ADD", $fstDealerCode = "")
	{
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Dealer" : "Update Dealer";
		$data["fstDealerCode"] = $fstDealerCode;

		$page_content = $this->parser->parse('pages/master/dealers/form', $data, true);
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
		$this->openForm("ADD", "");
	}

	public function edit($fstDealerCode)
	{
		parent::edit($fstDealerCode);
		$this->openForm("EDIT", $fstDealerCode);
	}

	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('msdealers_model');
		
		$this->form_validation->set_rules($this->msdealers_model->getRules("ADD", ""));
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
			"fstDealerCode" => $this->input->post("fstDealerCode"),
			"fstDealerName" => $this->input->post("fstDealerName"),
			"fstPersonInCharge" => $this->input->post("fstPersonInCharge"),
			"fstPhoneNo" => $this->input->post("fstPhoneNo"),
			"fstAddress" => $this->input->post("fstAddress"),
			"fstEmail" => $this->input->post("fstEmail"),
			"fstGenesysDealerCode" => $this->input->post("fstGenesysDealerCode"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->msdealers_model->insert($data);
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
		$this->load->model('msdealers_model');
		$fstDealerCode = $this->input->post("fstDealerCode");
		$data = $this->msdealers_model->getDataById($fstDealerCode);
		$dealer = $data["dealers"];
		if (!$dealer) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fstDealerCode Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->msdealers_model->getRules("EDIT", $fstDealerCode));
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
			"fstDealerCode" => $this->input->post("fstDealerCode"),
			"fstDealerName" => $this->input->post("fstDealerName"),
			"fstPersonInCharge" => $this->input->post("fstPersonInCharge"),
			"fstPhoneNo" => $this->input->post("fstPhoneNo"),
			"fstAddress" => $this->input->post("fstAddress"),
			"fstEmail" => $this->input->post("fstEmail"),
			"fstGenesysDealerCode" => $this->input->post("fstGenesysDealerCode"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();

		$this->msdealers_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $fstDealerCode;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
		$this->datatables->setTableName("tbdealers");

		$selectFields = "fstDealerCode,fstDealerName,fstPersonInCharge,fstGenesysDealerCode,fstAddress,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["fstDealerCode"] . "'><i class='fa fa-pencil'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fstDealerCode)
	{
		$this->load->model("msdealers_model");
		$data = $this->msdealers_model->getDataById($fstDealerCode);

		//if($data["fst_branchtemp_dbconnstring"] !=""){
		//	$data["fst_branchtemp_dbconnstring"] = $data["fst_branchtemp_dbconnstring"];
		//}

		//$this->load->library("datatables");		
		$this->json_output($data);
	}

	public function delete($id){
		parent::delete($id);
		$this->db->trans_start();
		$this->msdealers_model->delete($id);
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$result = $this->msdealers_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}
}
