<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bpkbtransferin extends MY_Controller
{
	public $menuName="bpkbtransferin"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trbpkbtransferin_model');
        $this->load->model('trbpkbtransferin_detail_model');
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
		$this->list['page_name'] = "BPKB Transfer IN";
		$this->list['list_name'] = "BPKB Transfer IN List";
		$this->list['addnew_ajax_url'] = site_url() . 'trx/bpkbtransferin/add';
		//$this->list['report_url'] = site_url() . 'report/principalinvoices';
		$this->list['pKey'] = "fstTransferInNo";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'trx/bpkbtransferin/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'trx/bpkbtransferin/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'trx/bpkbtransferin/edit/';
		$this->list['arrSearch'] = [
			'fstTransferInNo' => 'Transfer IN No.',
			'fstTransferOutNo' => 'Transfer OUT No',
            'fstMemo' => 'Memo'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Transaction', 'link' => '#', 'icon' => ''],
			['title' => 'BPKB Transfer IN', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'Transfer No', 'width' => '7%', 'data' => 'fstTransferInNo'],
			['title' => 'Transfer Date', 'width' => '7%', 'data' => 'fdtTransferInDate'],
            ['title' => 'Transfer Out No', 'width' => '7%', 'data' => 'fstTransferOutNo'],
            ['title' => 'Warehouse', 'width' => '10%', 'data' => 'fstWarehouseName'],
			['title' => 'Memo', 'width' => '20%', 'data' => 'fstMemo'],
			['title' => 'Action', 'width' => '7%', 'sortable' => false, 'className' => 'text-center',
			'render'=>"function(data,type,row){
				action = '<div style=\"font-size:16px\">';
				action += '<a class=\"btn-edit\" href=\"".site_url()."trx/bpkbtransferin/edit/' + row.fstTransferInNo + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
				action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
				action += '<div>';
				return action;
			}"
		]
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

	public function openForm($mode="ADD",$fstTransferInNo=0){
        $this->load->library("menus");
		$this->load->model("msbpkbwarehouse_model");
        $this->load->model("trbpkbtransferout_model");
		
		if($this->input->post("submit") != "" ){
			$this->add_save();
		}

        $main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add BPKB Transfer IN" : "Update BPKB Transfer IN";
		$data["fstTransferInNo"] = $fstTransferInNo;
        $fstTransferInNo = $this->trbpkbtransferin_model->GenerateNo();
        
		$page_content = $this->parser->parse('pages/tr/bpkbtransferin/form',$data,true);
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
        $fstTransferInNo = $this->trbpkbtransferin_model->GenerateNo();
		$this->openForm("ADD", $fstTransferInNo);
	}

	public function edit($fstTransferInNo){
        $this->openForm("EDIT",$fstTransferInNo);
    }


	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('trbpkbtransferin_model');
		$this->form_validation->set_rules($this->trbpkbtransferin_model->getRules("ADD", ""));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}

        $fstTransferInNo = $this->trbpkbtransferin_model->GenerateNo();

		$data = [
			"fstTransferInNo" => $fstTransferInNo,
			"fdtTransferInDate"=>dBDateFormat($this->input->post("fdtTransferInDate")),
			"fstTransferOutNo" => $this->input->post("fstTransferOutNo"),
            "finWarehouseId" => $this->input->post("finWarehouseId"),
            "fstMemo" => $this->input->post("fstMemo"),
			"fst_active" => 'A'
		];
		$this->db->trans_start();
		$insertId = $this->trbpkbtransferin_model->insert($data);

		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

        //Save Transfer In Detail
        $this->load->model("trbpkbtransferin_detail_model");
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $detail) {
            $data = [
                "fstTransferInNo" => $fstTransferInNo,
                "fstBpkbNo" => $detail->fstBpkbNo,
                "fstNotes" => $detail->fstNotes,
                "fst_active" => 'A'
            ];
            $this->trbpkbtransferin_detail_model->insert($data);
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
        $this->trbpkbtransferin_model->postingLogBpkb($fstTransferInNo);
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function ajx_edit_save()
	{
		parent::ajx_edit_save();
		$this->load->model('trbpkbtransferin_model');
		$fstTransferInNo = $this->input->post('fstTransferInNo');
		$data = $this->trbpkbtransferin_model->getDataById($fstTransferInNo);
		$transferin = $data["transferIn"];
		if (!$transferin) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fstTransferInNo Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->trbpkbtransferin_model->getRules("EDIT", $fstTransferInNo));
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
			"fstTransferInNo" => $fstTransferInNo,
			"fdtTransferInDate"=>dBDateFormat($this->input->post("fdtTransferInDate")),
			"fstTransferOutNo" => $this->input->post("fstTransferOutNo"),
            "finWarehouseId" => $this->input->post("finWarehouseId"),
            "fstMemo" => $this->input->post("fstMemo"),
			"fst_active" => 'A'
		];

        $this->db->trans_start();
		$this->trbpkbtransferin_model->update($data);

        //Save Detail Transfer In
        $this->load->model("trbpkbtransferin_detail_model");
        $this->trbpkbtransferin_detail_model->deleteByHeaderId($fstTransferInNo);
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $detail) {
            $data = [
                "fstTransferInNo" => $fstTransferInNo,
                "fstBpkbNo" => $detail->fstBpkbNo,
                "fstNotes" => $detail->fstNotes,
                "fst_active" => 'A'
            ];
            $this->trbpkbtransferin_detail_model->insert($data);
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
        $this->trbpkbtransferin_model->unpostingLogBpkb($fstTransferInNo);
        $this->trbpkbtransferin_model->postingLogBpkb($fstTransferInNo);
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $fstTransferInNo;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
        $this->datatables->setTableName("(SELECT a.*,b.fstWarehouseName FROM trbpkbtransferin a LEFT JOIN tbbpkbwarehouse b ON a.finWarehouseId = b.finWarehouseId)a");

		$selectFields = "fstTransferInNo,fdtTransferInDate,fstTransferOutNo,fstWarehouseName,fstMemo,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$fdtTransferInDate = strtotime($data["fdtTransferInDate"]);
			$data["fdtTransferInDate"] = date("d-M-Y",$fdtTransferInDate);
		

			//action
			$data["action"]	= "<div style='font-size:16px'>
				<a class='btn-delete' href='#' data-id='" . $data["fstTransferInNo"] . "'><i class='fa fa-trash'></i></a>
			</div>";

			$arrDataFormated[] = $data;
		}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fstTransferInNo)
	{
		$this->load->model('trbpkbtransferin_model');
		$data = $this->trbpkbtransferin_model->getDataById($fstTransferInNo);
		$this->json_output($data);
	}

	public function delete($fstTransferInNo){
		parent::delete($fstTransferInNo);
		$this->load->model('trbpkbtransferin_model');
        $this->load->model("trbpkbtransferin_detail_model");
        //$data = $this->trbpkbtransferin_model->getDataById($fstTransferInNo);
		//$approved = $data["request"];
        //if($approved->fstTrxPICApprovedBy =="" || $approved->fstTrxPICApprovedBy == null ){
            $this->db->trans_start();
            $this->trbpkbtransferin_model->delete($fstTransferInNo);
            $this->trbpkbtransferin_detail_model->deleteByHeaderId($fstTransferInNo);
            $this->db->trans_complete();
    
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = lang("Data deleted !");
            $this->json_output();
        /*}else{
            $this->ajxResp["status"] = "";
            $this->ajxResp["message"] = lang("Can't delete !");
            $this->json_output();
        }*/
	}

	public function getAllList()
	{
		$this->load->model('trbpkbtransferin_model');
		$result = $this->trbpkbtransferin_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}

    public function valid()
	{
        $fstBpkbNo = $this->input->post("fstBpkbNo");
        $fstTransferOutNo = $this->input->post("fstTransferOutNo");

        $this->load->model('Trbpkbtransferout_detail_model');
		$data = $this->Trbpkbtransferout_detail_model->cekbpkb($fstBpkbNo,$fstTransferOutNo);
		$outbpkb = $data["outbpkb"];
		if (!$outbpkb) {
            $this->ajxResp["status"] = "NOT VALID";
            $this->ajxResp["message"] = "Please check Transfer Out No.";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
		}else{
            $this->ajxResp["status"] = "VALID";
            $this->ajxResp["message"] = "";
            $this->ajxResp["data"] = [];
            $this->json_output();
        }

	}

    public function get_outpending_list(){
        $this->load->model('trbpkbtransferout_model');
        $rs = $this->trbpkbtransferout_model->getPendingOutList();

        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
        $this->json_output();
    }

}
