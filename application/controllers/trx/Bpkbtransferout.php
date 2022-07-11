<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bpkbtransferout extends MY_Controller
{
	public $menuName="bpkbtransferout"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trbpkbtransferout_model');
        $this->load->model('trbpkbtransferout_detail_model');
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
		$this->list['page_name'] = "BPKB Transfer Out";
		$this->list['list_name'] = "BPKB Transfer Out List";
		$this->list['addnew_ajax_url'] = site_url() . 'trx/bpkbtransferout/add';
		//$this->list['report_url'] = site_url() . 'report/principalinvoices';
		$this->list['pKey'] = "fstTransferOutNo";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'trx/bpkbtransferout/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'trx/bpkbtransferout/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'trx/bpkbtransferout/edit/';
        $this->list['warehouse_match'] = site_url() . 'trx/bpkbtransferout/iswarehousematch/';
		$this->list['arrSearch'] = [
			'fstTransferOutNo' => 'Transfer No.',
			'fstReqNo' => 'Request No',
            'fstMemo' => 'Memo'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Transaction', 'link' => '#', 'icon' => ''],
			['title' => 'BPKB Transfer Out', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'Transfer No', 'width' => '7%', 'data' => 'fstTransferOutNo'],
			['title' => 'Transfer Date', 'width' => '7%', 'data' => 'fdtTransferOutDate'],
            ['title' => 'Type', 'width' => '10%', 'data' => 'finTransferType',
                'render'=>"function(data,type,row){
                    switch (data){
                        case '1':
                            return 'Mutasi Gudang';
                            break;
                        case '2':
                            return 'Request Checkout';
                            break;
                        case '3':
                            return 'Request Dealer';
                            break;
                        case '4':
                            return 'Peminjaman';
                            break;  
                        case '5':
                            return 'Perbaikan';
                            break;
                        case '6':
                            return 'Proses Ulang';
                            break;
                        default:
                            return null; 
                    }   
                }"
            ],
            ['title' => 'Request No', 'width' => '7%', 'data' => 'fstReqNo'],
			['title' => 'Memo', 'width' => '20%', 'data' => 'fstMemo'],
			['title' => 'Action', 'width' => '7%', 'sortable' => false, 'className' => 'text-center',
			'render'=>"function(data,type,row){
				action = '<div style=\"font-size:16px\">';
				action += '<a class=\"btn-edit\" href=\"".site_url()."trx/bpkbtransferout/edit/' + row.fstTransferOutNo + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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

	public function openForm($mode="ADD",$fstTransferOutNo=0){
        $this->load->library("menus");
		$this->load->model("Msbpkbwarehouse_model");
        $this->load->model("Trbpkbrequest_model");
        $this->load->model("Trbpkbtransferout_model");
		
		if($this->input->post("submit") != "" ){
			$this->add_save();
		}

        $main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add BPKB Transfer Out" : "Update BPKB Transfer Out";
		$data["fstTransferOutNo"] = $fstTransferOutNo;
        $fstTransferOutNo = $this->trbpkbtransferout_model->GenerateNo();
        
		$page_content = $this->parser->parse('pages/tr/bpkbtransferout/form',$data,true);
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
        $fstTransferOutNo = $this->trbpkbtransferout_model->GenerateNo();
		$this->openForm("ADD", $fstTransferOutNo);
	}

	public function edit($fstTransferOutNo){
        $this->openForm("EDIT",$fstTransferOutNo);
    }


	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('trbpkbtransferout_model');
		$this->form_validation->set_rules($this->trbpkbtransferout_model->getRules("ADD", ""));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}

        $fstTransferOutNo = $this->trbpkbtransferout_model->GenerateNo();

		$data = [
			"fstTransferOutNo" => $fstTransferOutNo,
			"fdtTransferOutDate"=>dBDateFormat($this->input->post("fdtTransferOutDate")),
            "finTransferType" => $this->input->post("finTransferType"),
			"fstReqNo" => $this->input->post("fstReqNo"),
            "finFromWarehouseId" => $this->input->post("finFromWarehouseId"),
			"finToWarehouseId" => $this->input->post("finToWarehouseId"),
            "fstMemo" => $this->input->post("fstMemo"),
            "fdbDaysToWarehouse" => $this->input->post("fdbDaysToWarehouse"),
			"fst_active" => 'A'
		];
		$this->db->trans_start();
		$insertId = $this->trbpkbtransferout_model->insert($data);

		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

        //Save Transfer Out Detail
        $this->load->model("trbpkbtransferout_detail_model");
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $detail) {
            $data = [
                "fstTransferOutNo" => $fstTransferOutNo,
                "fstBpkbNo" => $detail->fstBpkbNo,
                "fstNotes" => $detail->fstNotes,
                "fst_active" => 'A'
            ];
            $this->trbpkbtransferout_detail_model->insert($data);
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
        $this->trbpkbtransferout_model->postingLogBpkb($fstTransferOutNo);
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function ajx_edit_save()
	{
		parent::ajx_edit_save();
		$this->load->model('trbpkbtransferout_model');
		$fstTransferOutNo = $this->input->post('fstTransferOutNo');
		$data = $this->trbpkbtransferout_model->getDataById($fstTransferOutNo);
		$transferout = $data["transferOut"];
		if (!$transferout) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fstTransferOutNo Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->trbpkbtransferout_model->getRules("EDIT", $fstTransferOutNo));
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
			"fstTransferOutNo" => $fstTransferOutNo,
			"fdtTransferOutDate"=>dBDateFormat($this->input->post("fdtTransferOutDate")),
            "finTransferType" => $this->input->post("finTransferType"),
			"fstReqNo" => $this->input->post("fstReqNo"),
            "finFromWarehouseId" => $this->input->post("finFromWarehouseId"),
			"finToWarehouseId" => $this->input->post("finToWarehouseId"),
            "fstMemo" => $this->input->post("fstMemo"),
            "fdbDaysToWarehouse" => $this->input->post("fdbDaysToWarehouse"),
			"fst_active" => 'A'
		];

        $this->db->trans_start();
        $this->trbpkbtransferout_model->unpostingLogBpkb($fstTransferOutNo);
		$this->trbpkbtransferout_model->update($data);

        //Save Detail Transfer Out
        $this->load->model("trbpkbtransferout_detail_model");
        $this->trbpkbtransferout_detail_model->deleteByHeaderId($fstTransferOutNo);
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $detail) {
            $data = [
                "fstTransferOutNo" => $fstTransferOutNo,
                "fstBpkbNo" => $detail->fstBpkbNo,
                "fstNotes" => $detail->fstNotes,
                "fst_active" => 'A'
            ];
            $this->trbpkbtransferout_detail_model->insert($data);
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

        $this->trbpkbtransferout_model->postingLogBpkb($fstTransferOutNo);
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $fstTransferOutNo;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
        $this->datatables->setTableName("trbpkbtransferout");

		$selectFields = "fstTransferOutNo,fdtTransferOutDate,finTransferType,fstReqNo,fstMemo,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$fdtTransferOutDate = strtotime($data["fdtTransferOutDate"]);
			$data["fdtTransferOutDate"] = date("d-M-Y",$fdtTransferOutDate);
		

			//action
			$data["action"]	= "<div style='font-size:16px'>
				<a class='btn-delete' href='#' data-id='" . $data["fstTransferOutNo"] . "'><i class='fa fa-trash'></i></a>
			</div>";

			$arrDataFormated[] = $data;
		}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fstTransferOutNo)
	{
		$this->load->model('trbpkbtransferout_model');
		$data = $this->trbpkbtransferout_model->getDataById($fstTransferOutNo);
		$this->json_output($data);
	}

	public function delete($fstTransferOutNo){
		parent::delete($fstTransferOutNo);
		$this->load->model('trbpkbtransferout_model');
        $this->load->model("trbpkbtransferout_detail_model");
        $data = $this->trbpkbtransferout_model->getDataById($fstTransferOutNo);
		$approved = $data["request"];
        if($approved->fstTrxPICApprovedBy =="" || $approved->fstTrxPICApprovedBy == null ){
            $this->db->trans_start();
            $this->trbpkbtransferout_model->delete($fstTransferOutNo);
            $this->trbpkbtransferout_detail_model->deleteByHeaderId($fstTransferOutNo);
            $this->db->trans_complete();
    
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = lang("Data deleted !");
            $this->json_output();
        }else{
            $this->ajxResp["status"] = "";
            $this->ajxResp["message"] = lang("Can't delete !");
            $this->json_output();
        }
	}

	public function getAllList()
	{
		$this->load->model('trbpkbtransferout_model');
		$result = $this->trbpkbtransferout_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}

    public function get_request_list(){

        $rs = $this->trbpkbtransferout_model->get_RequestList($this->input->get("finTransferType"));

        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
        $this->json_output();
    }

    public function ajxBpkbData(){

        $customer_name = $this->input->post("fstCustomerName");
        $nik = $this->input->post("fstNik");
        $bpkb_no = $this->input->post("fstBpkbNo");
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
        if ($bpkb_no != "") {
            $swhere .= " and a.fstBpkbNo = " . $this->db->escape($bpkb_no);
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

            $ssql = "SELECT a.*,b.fstBrandCode,b.fstBrandName FROM trbpkb a LEFT JOIN tbbrandtypes b ON a.finBrandTypeId = b.finBrandTypeId  $swhere GROUP BY a.fstBpkbNo";
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

    public function valid()
	{
        $fstBpkbNo = $this->input->post("fstBpkbNo");
        $finWarehouseId = $this->input->post("finWarehouseId");
        $fstReqNo = $this->input->post("fstReqNo");

        $this->load->model('trbpkbtransferout_model');
		$data = $this->trbpkbtransferout_model->getBpkbNo($fstBpkbNo);
		$bpkb = $data["bpkb"];
		if (!$bpkb) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "BPKB No $fstBpkbNo Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}else{
            $warehouse = $this->trbpkbtransferout_model->cekWarehouse($fstBpkbNo,$finWarehouseId);
            $warehousebpkb = $warehouse["warehousebpkb"];
            if (!$warehousebpkb) {
                $this->ajxResp["status"] = "NOT VALID";
                $this->ajxResp["message"] = "Warehouse Not Match";
                $this->ajxResp["data"] = [];
                $this->json_output();
                return;
            }else if($fstReqNo !='' OR $fstReqNo != null){
                $this->load->model('Trbpkbrequest_detail_model');
                $request = $this->Trbpkbrequest_detail_model->cekbpkb($fstBpkbNo,$fstReqNo);
                $requestbpkb = $request["requestbpkb"];
                if(!$requestbpkb){
                    $this->ajxResp["status"] = "NOT VALID";
                    $this->ajxResp["message"] = "Check BPKB Request";
                    $this->ajxResp["data"] = [];
                    $this->json_output();
                    return;
                }else{
                    $this->ajxResp["status"] = "VALID";
                    $this->ajxResp["message"] = "";
                    $this->ajxResp["data"] = [];
                    $this->json_output();
                }
            }else{
                $this->ajxResp["status"] = "VALID";
                $this->ajxResp["message"] = "";
                $this->ajxResp["data"] = [];
                $this->json_output();
            }
        }

	}

}
