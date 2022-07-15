<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Colours extends MY_Controller
{

	public $menuName="colour"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('mscolours_model');
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
		$this->list['page_name'] = "Colour";
		$this->list['list_name'] = "Colour List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/colours/add';
		$this->list['report_url'] = site_url() . 'report/Colour';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/colours/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/colours/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/colours/edit/';
		$this->list['arrSearch'] = [
			'fstColourCode' => 'Colour ID',
			'fstColourName' => 'Colour Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Colour', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Colour Code', 'width' => '10%', 'data' => 'fstColourCode'],
			['title' => 'Colour Name', 'width' => '15%', 'data' => 'fstColourName'],
			['title' => 'Genesys Code', 'width' => '10%', 'data' => 'fstGenesysColourCode'],
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

	private function openForm($mode = "ADD", $fstColourCode = "")
	{
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Colour" : "Update Colour";
		$data["fstColourCode"] = $fstColourCode;

		$page_content = $this->parser->parse('pages/master/colours/form', $data, true);
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

	public function edit($fstColourCode)
	{
		parent::edit($fstColourCode);
		$this->openForm("EDIT", $fstColourCode);
	}

	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('mscolours_model');
		
		$this->form_validation->set_rules($this->mscolours_model->getRules("ADD", ""));
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
			"fstColourCode" => $this->input->post("fstColourCode"),
			"fstColourName" => $this->input->post("fstColourName"),
			"fstGenesysColourCode" => $this->input->post("fstGenesysColourCode"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->mscolours_model->insert($data);
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
		$this->load->model('mscolours_model');
		$fstColourCode = $this->input->post("fstColourCode");
		$data = $this->mscolours_model->getDataById($fstColourCode);
		$colour = $data["colours"];
		if (!$colour) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fstColourCode Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->mscolours_model->getRules("EDIT", $fstColourCode));
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
			"fstColourCode" => $this->input->post("fstColourCode"),
			"fstColourName" => $this->input->post("fstColourName"),
			"fstGenesysColourCode" => $this->input->post("fstGenesysColourCode"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();

		$this->mscolours_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $fstColourCode;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
		$this->datatables->setTableName("tbcolours");

		$selectFields = "fstColourCode,fstColourName,fstGenesysColourCode,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["fstColourCode"] . "'><i class='fa fa-pencil'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fstColourCode)
	{
		$this->load->model("mscolours_model");
		$data = $this->mscolours_model->getDataById($fstColourCode);

		//if($data["fst_Colourtemp_dbconnstring"] !=""){
		//	$data["fst_Colourtemp_dbconnstring"] = $data["fst_Colourtemp_dbconnstring"];
		//}

		//$this->load->library("datatables");		
		$this->json_output($data);
	}

	public function delete($id){
		parent::delete($id);
		$this->db->trans_start();
		$this->mscolours_model->delete($id);
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$result = $this->mscolours_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}
}
