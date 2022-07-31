<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bpkbopname extends MY_Controller
{
	public $menuName="bpkbopname"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
        $this->load->model("msbpkbwarehouse_model");
		$this->load->model('trbpkbopname_model');
        $this->load->model('trbpkbopname_detail_model');
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
        $trxDate = dBDateFormat($this->input->post("fdtOpnameStartDate"));
        $fstOpnameNo = $this->trbpkbopname_model->GenerateNo($trxDate);
        $this->list['fstOpnameNo'] = $fstOpnameNo;
        $mode = "ADD";
        $this->list["mode"] = $mode;
		$this->list["title"] = $mode == "ADD" ? "Add NEW BPKB Opname" : "Update BPKB Opname";
		$this->list['page_name'] = "BPKB Opname";
		$this->list['list_name'] = "BPKB Opname List";
		$this->list['addnew_ajax_url'] = site_url() . 'trx/bpkbopname/add';
		//$this->list['report_url'] = site_url() . 'report/principalinvoices';
		$this->list['pKey'] = "fstOpnameNo";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'trx/bpkbopname/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'trx/bpkbopname/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'trx/bpkbopname/edit/';
        $this->list['isvalid'] = site_url() . 'trx/bpkbopname/isvalid/';
		$this->list['arrSearch'] = [
			'fstOpnameNo' => 'Opname No.',
			'fstWarehouseName' => 'Warehouse'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Transaction', 'link' => '#', 'icon' => ''],
			['title' => 'BPKB Opname', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'Opname No', 'width' => '5%', 'data' => 'fstOpnameNo'],
			['title' => 'Start Date', 'width' => '7%', 'data' => 'fdtOpnameStartDate'],
            ['title' => 'End Date', 'width' => '7%', 'data' => 'fdtOpnameEndDate'],
            ['title' => 'Warehouse', 'width' => '10%', 'data' => 'fstWarehouseName'],
			['title' => 'Status', 'width' => '7%', 'data' => 'fstOpnameStatus'],
			['title' => 'Action', 'width' => '10%', 'sortable' => false, 'className' => 'text-center',
                'render'=>"function(data,type,row){
                    action = '<div style=\"font-size:16px\">';
                    action += '<a class=\"btn-opname\" href=\"#\" data-id=\"\">Opname</a>&nbsp;&nbsp;&nbsp;';
                    action += '<a class=\"btn-close\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" >Close</a>&nbsp;&nbsp;&nbsp;';
                    action += '<a class=\"btn-view\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" >View</a>';
                    action += '<div>';
                    return action;
                }"
            ]
		];

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = $this->parser->parse('pages/tr/bpkbopname/list', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = null;
		$this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
		$this->data['MAIN_HEADER'] = $main_header;
		$this->data['MAIN_SIDEBAR'] = $main_sidebar;
		$this->data['PAGE_CONTENT'] = $page_content;
		$this->data['MAIN_FOOTER'] = $main_footer;
		$this->parser->parse('template/main', $this->data);
	}

    public function process()
	{
		parent::index();
		$this->load->library('menus');
        $trxDate = dBDateFormat($this->input->post("fdtOpnameStartDate"));
        $fstOpnameNo = $this->trbpkbopname_model->GenerateNo($trxDate);
        $this->list['fstOpnameNo'] = $fstOpnameNo;
        $mode = "ADD";
        $this->list["mode"] = $mode;
		$this->list["title"] = $mode == "ADD" ? "Add NEW BPKB Opname" : "Update BPKB Opname";
		$this->list['page_name'] = "Opname Proses";
		$this->list['list_name'] = "Opname Proses List";
		$this->list['addnew_ajax_url'] = site_url() . 'trx/bpkbopname/add';
		//$this->list['report_url'] = site_url() . 'report/principalinvoices';
		$this->list['pKey'] = "fstOpnameNo";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'trx/bpkbopname/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'trx/bpkbopname/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'trx/bpkbopname/edit/';
        $this->list['isvalid'] = site_url() . 'trx/bpkbopname/isvalid/';
		$this->list['arrSearch'] = [
			'fstOpnameNo' => 'Opname No.',
			'fstWarehouseName' => 'Warehouse'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Transaction', 'link' => '#', 'icon' => ''],
			['title' => 'Opname Proses', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'Opname No', 'width' => '5%', 'data' => 'fstOpnameNo'],
			['title' => 'Start Date', 'width' => '7%', 'data' => 'fdtOpnameStartDate'],
            ['title' => 'End Date', 'width' => '7%', 'data' => 'fdtOpnameEndDate'],
            ['title' => 'Warehouse', 'width' => '10%', 'data' => 'fstWarehouseName'],
			['title' => 'Status', 'width' => '7%', 'data' => 'fstOpnameStatus'],
			['title' => 'Action', 'width' => '10%', 'sortable' => false, 'className' => 'text-center',
                'render'=>"function(data,type,row){
                    action = '<div style=\"font-size:16px\">';
                    action += '<a class=\"btn-view\" href=\"#\" data-id=\"\">VIEW</a>&nbsp;&nbsp;&nbsp;';
                    action += '<a class=\"btn-process\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" >PROCESS</a>&nbsp;&nbsp;&nbsp;';
                    action += '<a class=\"btn-view-result\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" >VIEW RESULT</a>';
                    action += '<div>';
                    return action;
                }"
            ]
		];

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = $this->parser->parse('pages/tr/bpkbopname/process', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = null;
		$this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
		$this->data['MAIN_HEADER'] = $main_header;
		$this->data['MAIN_SIDEBAR'] = $main_sidebar;
		$this->data['PAGE_CONTENT'] = $page_content;
		$this->data['MAIN_FOOTER'] = $main_footer;
		$this->parser->parse('template/main', $this->data);
	}

	public function openForm($mode="ADD",$fstOpnameNo=0){
        $this->load->library("menus");
        $this->load->model("trbpkbopname_model");
		
		if($this->input->post("submit") != "" ){
			$this->add_save();
		}

        $main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add BPKB Opname" : "Update BPKB Opname";
		$data["fstOpnameNo"] = $fstOpnameNo;
        $fstOpnameNo = $this->trbpkbopname_model->GenerateNo();
        
		$page_content = $this->parser->parse('pages/tr/bpkbopname/form',$data,true);
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
        $fstOpnameNo = $this->trbpkbopname_model->GenerateNo();
		$this->openForm("ADD", $fstOpnameNo);
	}

	public function edit($fstOpnameNo){
        $this->openForm("EDIT",$fstOpnameNo);
    }

	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('trbpkbopname_model');
		$this->form_validation->set_rules($this->trbpkbopname_model->getRules("ADD", ""));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}
        //$fdtOpnameStartDate = dBDateFormat($this->input->post("fdtOpnameStartDate"));
        $fdtOpnameStartDatetime = strtotime($this->input->post("fdtOpnameStartDate"));
        $fdtOpnameStartDate = date("Y-m-d",$fdtOpnameStartDatetime);
        $warehouse = $this->input->post('finWarehouseId');
		$trx = $this->trbpkbopname_model->opnameExist($fdtOpnameStartDate,$warehouse);
		$opnameExist = $trx["opname"];
		if ($opnameExist) {
			$this->ajxResp["status"] = "DATA_EXIST";
			$this->ajxResp["message"] = "Date: $fdtOpnameStartDate + Warehouse: $warehouse conflict by $opnameExist->fstOpnameNo !!!";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

        $fstOpnameNo = $this->trbpkbopname_model->GenerateNo($fdtOpnameStartDate);
		$data = [
			"fstOpnameNo" => $fstOpnameNo,
			"fdtOpnameStartDate" =>dBDateTimeFormat($this->input->post("fdtOpnameStartDate")),
            "fdtOpnameEndDate" => null,
			//"fstOpnameStatus" => $this->input->post("fstOpnameStatus"),
            "finWarehouseId" => $this->input->post("finWarehouseId"),
            "fstMemo" => $this->input->post("fstMemo"),
			"fst_active" => 'A'
		];
		$this->db->trans_start();
		$insertId = $this->trbpkbopname_model->insert($data);

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
        $user = $this->aauth->user();
        $activeUser = $user->fstUserCode;
        $mode = "EDIT";
		$this->load->model('trbpkbopname_model');
		$fstOpnameNo = $this->input->post('fstOpnameNo');
		$data = $this->trbpkbopname_model->getDataById($fstOpnameNo,$mode);
		$opname = $data["opnameHeader"];
		if (!$opname) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fstOpnameNo Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

        if ($opname->fin_insert_id == $activeUser){
            $this->form_validation->set_rules($this->trbpkbopname_model->getRules("EDIT", $fstOpnameNo));
            $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
            if ($this->form_validation->run() == FALSE) {
                //print_r($this->form_validation->error_array());
                $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
                $this->ajxResp["message"] = "Error Validation Forms";
                $this->ajxResp["data"] = $this->form_validation->error_array();
                $this->json_output();
                return;
            }
            //$fdtOpnameStartDate = dBDateFormat($this->input->post("fdtOpnameStartDate"));
            $fdtOpnameStartDatetime = strtotime($this->input->post("fdtOpnameStartDate"));
            $fdtOpnameStartDate = date("Y-m-d",$fdtOpnameStartDatetime);
            $warehouse = $this->input->post('finWarehouseId');
            $trx = $this->trbpkbopname_model->opnameExist($fdtOpnameStartDate,$warehouse);
            $opnameExist = $trx["opname"];
            if ($opnameExist){
                if ($opnameExist->fstOpnameNo != $fstOpnameNo) {
                    $this->ajxResp["status"] = "DATA_EXIST";
                    $this->ajxResp["message"] = "Date: $fdtOpnameStartDate + Warehouse: $warehouse conflict by $opnameExist->fstOpnameNo !!!";
                    $this->ajxResp["data"] = [];
                    $this->json_output();
                    return;
                }
            }
        }

		$data = [
			"fstOpnameNo" => $fstOpnameNo,
			"fdtOpnameStartDate"=>dBDateTimeFormat($this->input->post("fdtOpnameStartDate")),
			//"fdtOpnameEndDate" =>dBDateFormat($this->input->post("fdtOpnameEndDate")),
            "fstOpnameStatus" => $this->input->post("fstOpnameStatus"),
            "finWarehouseId" => $this->input->post("finWarehouseId"),
            "fstMemo" => $this->input->post("fstMemo"),
			"fst_active" => 'A'
		];

        $this->db->trans_start();
        if ($opname->fin_insert_id == $activeUser){
		    $this->trbpkbopname_model->update($data);
        }

        //Save Detail Transfer In
        $this->load->model("trbpkbopname_detail_model");
        $this->trbpkbopname_detail_model->deleteByHeaderId($fstOpnameNo);
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $detail) {
            $data = [
                "fstOpnameNo" => $fstOpnameNo,
                "fstBpkbNo" => $detail->fstBpkbNo,
                "fstBpkbOpnameStatus" => $detail->fstBpkbOpnameStatus,
                "fstNotes" => $detail->fstNotes,
                "fst_active" => 'A'
            ];
            $this->trbpkbopname_detail_model->insert($data);
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
		$this->ajxResp["data"]["insert_id"] = $fstOpnameNo;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
        $this->datatables->setTableName("(SELECT a.*,b.fstWarehouseName FROM trbpkbopname a LEFT JOIN tbbpkbwarehouse b ON a.finWarehouseId = b.finWarehouseId)a");

		$selectFields = "fstOpnameNo,fdtOpnameStartDate,fdtOpnameEndDate,fstWarehouseName,fstOpnameStatus,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$fdtOpnameStartDate = strtotime($data["fdtOpnameStartDate"]);
			$data["fdtOpnameStartDate"] = date("d M Y - H:i:s",$fdtOpnameStartDate);
            if ($data["fdtOpnameEndDate"] != null || $data["fdtOpnameEndDate"] != ""){
                $fdtOpnameEndDate = strtotime($data["fdtOpnameEndDate"]);
                $data["fdtOpnameEndDate"] = date("d M Y - H:i:s",$fdtOpnameEndDate);
            }

			//action
			$data["action"]	= "<div style='font-size:16px'>
				<a class='btn-delete' href='#' data-id='" . $data["fstOpnameNo"] . "'><i class='fa fa-trash'></i></a>
			</div>";

			$arrDataFormated[] = $data;
		}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fstOpnameNo)
	{
        $mode = "EDIT";
		$this->load->model('trbpkbopname_model');
		$data = $this->trbpkbopname_model->getDataById($fstOpnameNo,$mode);
		$this->json_output($data);
	}

    public function view_data($fstOpnameNo)
	{
        $mode = "VIEW";
		$this->load->model('trbpkbopname_model');
		$data = $this->trbpkbopname_model->getDataById($fstOpnameNo,$mode);
		$this->json_output($data);
	}

	public function delete($fstOpnameNo){
		parent::delete($fstOpnameNo);
		$this->load->model("trbpkbopname_model");
        $this->load->model("trbpkbopname_detail_model");
        //$data = $this->trbpkbtransferin_model->getDataById($fstOpnameNo);
		//$approved = $data["request"];
        //if($approved->fstTrxPICApprovedBy =="" || $approved->fstTrxPICApprovedBy == null ){
            $this->db->trans_start();
            $this->trbpkbopname_model->delete($fstOpnameNo);
            $this->trbpkbopname_detail_model->deleteByHeaderId($fstOpnameNo);
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
		$this->load->model("trbpkbopname_model");
		$result = $this->trbpkbopname_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}

	public function isvalid($fstOpnameNo)
	{
		$this->load->model('trbpkbopname_model');
		$data = $this->trbpkbopname_model->getReady($fstOpnameNo);
		$bpkbopname = $data["bpkbopname"];
		if (!$bpkbopname) {
            $this->ajxResp["status"] = "NOT READY";
			$this->ajxResp["message"] = "Not Allowed to View";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}else{
            $this->ajxResp["status"] = "READY";
			$this->ajxResp["message"] = "";
			$this->ajxResp["data"] = [];
			$this->json_output();
        }
	}

    public function closeOpname(){
        $this->load->model('trbpkbopname_model');
        $fstOpnameNo = $this->input->post("fstOpnameNo");

        try{
		
			$this->db->trans_start();		
			$this->trbpkbopname_model->closeOpname($fstOpnameNo);		
			$this->db->trans_complete();

			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "";
			$this->json_output();
			
		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();			
			return;

		}
	}

}
