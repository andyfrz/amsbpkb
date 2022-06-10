<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bpkbtrxs extends MY_Controller
{

	public $menuName="bpkbtrxs"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('msbpkbtrxs_model');
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
		$this->list['page_name'] = "BPKB Trx";
		$this->list['list_name'] = "BPKB Trx List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/bpkbtrxs/add';
		$this->list['report_url'] = site_url() . 'report/bpkbtrxs';
		$this->list['pKey'] = "finTrxId";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/bpkbtrxs/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/bpkbtrxs/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/bpkbtrxs/edit/';
		$this->list['arrSearch'] = [
			'finTrxId' => 'ID',
            'fstTrxDescription' => 'Trx Discription'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'BPKB Trxs', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
            ['title' => 'ID', 'width' => '5%', 'data' => 'finTrxId'],
			['title' => 'Trx Description', 'width' => '20%', 'data' => 'fstTrxDescription'],
			['title' => 'System Trx', 'width' => '15%', 'data' => 'fblIsSystemTrx'],
            ['title' => 'Trx Type', 'width' => '10%', 'data' => 'fstTrxType'],
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

	private function openForm($mode = "ADD", $finTrxId = 0)
	{
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add BPKB Trx" : "Update BPKB Trx";
		$data["finTrxId"] = $finTrxId;

		$page_content = $this->parser->parse('pages/master/bpkbtrxs/form', $data, true);
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

	public function edit($finTrxId)
	{
		parent::edit($finTrxId);
		$this->openForm("EDIT", $finTrxId);
	}

	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('msbpkbtrxs_model');
		
		$this->form_validation->set_rules($this->msbpkbtrxs_model->getRules("ADD", 0));
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
			"fstTrxDescription" => $this->input->post("fstTrxDescription"),
            "fblIsSystemTrx" => $this->input->post("fblIsSystemTrx") == null? 0:1,
            "fstTrxType" => $this->input->post("fstTrxType"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->msbpkbtrxs_model->insert($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

        //Save Trx PIC Detail

		$this->load->model("Msbpkbtrxs_detail_model");
		$details = $this->input->post("detailuser");
		$details = json_decode($details);
		foreach ($details as $detailuser) {
			$data = [
				"finTrxId" => $insertId,
				"fstUserCode" => $detailuser->fstUserCode
			];
			$this->Msbpkbtrxs_detail_model->insert($data);
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
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function ajx_edit_save()
	{
		parent::ajx_edit_save();
		$this->load->model('msbpkbtrxs_model');
		$finTrxId = $this->input->post("finTrxId");
		$data = $this->msbpkbtrxs_model->getDataById($finTrxId);
		$bpkbtrx = $data["bpkbtrx"];
		if (!$bpkbtrx) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $finTrxId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->msbpkbtrxs_model->getRules("EDIT", $finTrxId));
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
            "finTrxId" => $finTrxId,
			"fstTrxDescription" => $this->input->post("fstTrxDescription"),
            "fblIsSystemTrx" => $this->input->post("fblIsSystemTrx") == null? 0:1,
            "fstTrxType" => $this->input->post("fstTrxType"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();

        $this->msbpkbtrxs_model->deleteDetail($finTrxId);
		$this->msbpkbtrxs_model->update($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

        //Save Trx PIC Detail

		$this->load->model("Msbpkbtrxs_detail_model");
        //$this->Msbpkbtrxs_detail_model->deleteByHeaderId($finTrxId);
		$details = $this->input->post("detailuser");
		$details = json_decode($details);
		foreach ($details as $detailuser) {
			$data = [
				"finTrxId" => $finTrxId,
				"fstUserCode" => $detailuser->fstUserCode
			];
			$this->Msbpkbtrxs_detail_model->insert($data);
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
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $finTrxId;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
		$this->datatables->setTableName("tbbpkbtrxs");

		$selectFields = "finTrxId,fstTrxDescription,fblIsSystemTrx,fstTrxType,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["finTrxId"] . "'><i class='fa fa-pencil'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($finTrxId)
	{
		$this->load->model("msbpkbtrxs_model");
		$data = $this->msbpkbtrxs_model->getDataById($finTrxId);

		//if($data["fst_Brandtemp_dbconnstring"] !=""){
		//	$data["fst_Brandtemp_dbconnstring"] = $data["fst_Brandtemp_dbconnstring"];
		//}

		//$this->load->library("datatables");		
		$this->json_output($data);
	}

	public function delete($finTrxId){
		parent::delete($finTrxId);
		$this->db->trans_start();
		$this->msbpkbtrxs_model->delete($finTrxId);
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$result = $this->msbpkbtrxs_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}
}
