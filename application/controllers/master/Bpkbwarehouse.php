<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bpkbwarehouse extends MY_Controller
{
	public $menuName="bpkbwarehouse"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
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
		$this->list['page_name'] = "Warehouse";
		$this->list['list_name'] = "Warehouse List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/bpkbwarehouse/add';
		$this->list['report_url'] = site_url() . 'report/bpkbwarehouse';
		$this->list['pKey'] = "finWarehouseId";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/bpkbwarehouse/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/bpkbwarehouse/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/bpkbwarehouse/edit/';
		$this->list['arrSearch'] = [
			'finWarehouseId' => 'Warehouse Code',
			'fstWarehouseName' => 'Warehouse Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Warehouse', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'ID', 'width' => '10%', 'data' => 'finWarehouseId'],
			['title' => 'Warehouse Name', 'width' => '25%', 'data' => 'fstWarehouseName'],
            ['title' => 'PIC', 'width' => '15%', 'data' => 'fstPersonInCharge'],
			['title' => 'Email', 'width' => '15%', 'data' => 'fstEmail'],
			['title' => 'Phone No', 'width' => '15%', 'data' => 'fstPhoneNo'],
            ['title' => 'Main', 'width' => '5%', 'data' => 'fblisMainWarehouse','className'=>'text-center',
                'render'=>"function(data,type,row){
                    var checked = data == 1 ? 'checked' : '';
                    return '<input type=\"checkbox\" ' + checked + ' disabled/>';
                }",
            ],
			['title' => 'Action', 'width' => '15%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
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

	public function openForm($mode = "ADD", $finWarehouseId = 0)
	{
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Warehouse" : "Update Warehouse";
		$data["finWarehouseId"] = $finWarehouseId;

		$page_content = $this->parser->parse('pages/master/bpkbwarehouse/form', $data, true);
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

	public function edit($finWarehouseId)
	{
		parent::edit($finWarehouseId);
		$this->openForm("EDIT", $finWarehouseId);
	}

	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('msbpkbwarehouse_model');
		$this->form_validation->set_rules($this->msbpkbwarehouse_model->getRules("ADD", ""));
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
			"fstWarehouseName" => $this->input->post("fstWarehouseName"),
			"fstPersonInCharge" => $this->input->post("fstPersonInCharge"),
			"fstEmail" => $this->input->post("fstEmail"),
			"fstPhoneNo" => $this->input->post("fstPhoneNo"),
            "fstAddress" => $this->input->post("fstAddress"),
			"fblisMainWarehouse" => $this->input->post("fblisMainWarehouse") == null? 0:1,
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->msbpkbwarehouse_model->insert($data);
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
		$this->load->model('msbpkbwarehouse_model');
		$finWarehouseId = $this->input->post('finWarehouseId');
		$data = $this->msbpkbwarehouse_model->getDataById($finWarehouseId);
		$warehouse = $data["bpkbwarehouse"];
		if (!$warehouse) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $finWarehouseId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->msbpkbwarehouse_model->getRules("EDIT", $finWarehouseId));
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
			"finWarehouseId" => $finWarehouseId,
			"fstWarehouseName" => $this->input->post("fstWarehouseName"),
			"fstPersonInCharge" => $this->input->post("fstPersonInCharge"),
			"fstEmail" => $this->input->post("fstEmail"),
			"fstPhoneNo" => $this->input->post("fstPhoneNo"),
            "fstAddress" => $this->input->post("fstAddress"),
			"fblisMainWarehouse" => $this->input->post("fblisMainWarehouse") == null? 0:1,
			"fst_active" => 'A'
		];

		$this->db->trans_start();

		$this->msbpkbwarehouse_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $finWarehouseId;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
		$this->datatables->setTableName("tbbpkbwarehouse");

		$selectFields = "finWarehouseId,fstWarehouseName,fstPersonInCharge,fstEmail,fstPhoneNo,fblisMainWarehouse,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//$birthdate = strtotime($data["fdt_birthdate"]);
			//$data["fdt_insert_datetime"] = dBDateFormat("fdt_birthdate");

			//action
			$data["action"]	= "<div style='font-size:16px'>
				<a class='btn-edit' href='#' data-id='" . $data["finWarehouseId"] . "'><i class='fa fa-pencil'></i></a>
			</div>";

			$arrDataFormated[] = $data;
		}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($finWarehouseId)
	{
		$this->load->model('msbpkbwarehouse_model');
		$data = $this->msbpkbwarehouse_model->getDataById($finWarehouseId);

		$this->json_output($data);
	}

	public function delete($id){
		parent::delete($id);
		$this->load->model('msbpkbwarehouse_model');
		$this->db->trans_start();
        $this->msbpkbwarehouse_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$this->load->model('msbpkbwarehouse_model');
		$result = $this->msbpkbwarehouse_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}

}
