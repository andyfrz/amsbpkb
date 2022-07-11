<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bpkbrequest extends MY_Controller
{
	public $menuName="bpkbrequest"; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trbpkbrequest_model');
        $this->load->model('trbpkbrequest_detail_model');
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
		$this->list['page_name'] = "BPKB Request";
		$this->list['list_name'] = "BPKB Request List";
		$this->list['addnew_ajax_url'] = site_url() . 'trx/bpkbrequest/add';
		//$this->list['report_url'] = site_url() . 'report/principalinvoices';
		$this->list['pKey'] = "fstReqNo";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'trx/bpkbrequest/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'trx/bpkbrequest/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'trx/bpkbrequest/edit/';
		$this->list['arrSearch'] = [
			'fstReqNo' => 'BPKB No.',
			'fstDealerName' => 'Dealer',
            'fstMemo' => 'Memo'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Transaction', 'link' => '#', 'icon' => ''],
			['title' => 'BPKB Request', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'Request No', 'width' => '7%', 'data' => 'fstReqNo'],
			['title' => 'Request Date', 'width' => '8%', 'data' => 'fdtReqDate'],
            ['title' => 'Dealer', 'width' => '10%', 'data' => 'fstDealerName'],
            ['title' => 'Type', 'width' => '10%', 'data' => 'finTransferType',
                'render'=>"function(data,type,row){
                    switch (data){
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
			['title' => 'Memo', 'width' => '20%', 'data' => 'fstMemo'],
            ['title' => 'Approved Date', 'width' => '8%', 'data' => 'fdtTrxPICApprovedDatetime'],
			['title' => 'Action', 'width' => '10%', 'sortable' => false, 'className' => 'text-center',
			'render'=>"function(data,type,row){
				action = '<div style=\"font-size:16px\">';
				action += '<a class=\"btn-edit\" href=\"".site_url()."trx/bpkbrequest/edit/' + row.fstReqNo + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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

	public function openForm($mode="ADD",$fstReqNo=0){
        $this->load->library("menus");
		$this->load->model("msdealers_model");
        $this->load->model("msbpkbtrxs_model");
		
		if($this->input->post("submit") != "" ){
			$this->add_save();
		}

        $main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Request" : "Update Request";
		$data["fstReqNo"] = $fstReqNo;
        $fstReqNo = $this->trbpkbrequest_model->GenerateNo();
        
		$page_content = $this->parser->parse('pages/tr/bpkbrequest/form',$data,true);
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
        $fstReqNo = $this->trbpkbrequest_model->GenerateNo();
		$this->openForm("ADD", $fstReqNo);
	}

	public function edit($fstReqNo){
        $this->openForm("EDIT",$fstReqNo);
    }


	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('trbpkbrequest_model');
		$this->form_validation->set_rules($this->trbpkbrequest_model->getRules("ADD", ""));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}

        $fstReqNo = $this->trbpkbrequest_model->GenerateNo();

		$data = [
			"fstReqNo" => $fstReqNo,
			"fdtReqDate"=>dBDateFormat($this->input->post("fdtReqDate")),
            "finTrxId" => $this->input->post("hfinTrxId"),
			"fstDealerCode" => $this->input->post("fstDealerCode"),
            "finTransferType" => $this->input->post("finTransferType"),
            "fstMemo" => $this->input->post("fstMemo"),
			"fst_active" => 'A'
		];
		$this->db->trans_start();
		$insertId = $this->trbpkbrequest_model->insert($data);

		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

        //Save Unit Detail
        $this->load->model("trbpkbrequest_detail_model");
        //$this->trbpkbrequest_detail_model->deleteByHeaderId($fstReqNo);
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $detail) {
            $data = [
                "fstReqNo" => $fstReqNo,
                "fstBpkbNo" => $detail->fstBpkbNo,
                "fstNotes" => $detail->fstNotes,
            ];
            $this->trbpkbrequest_detail_model->insert($data);
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
		$this->load->model('trbpkbrequest_model');
		$fstReqNo = $this->input->post('fstReqNo');
		$data = $this->trbpkbrequest_model->getDataById($fstReqNo);
		$request = $data["request"];
		if (!$request) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fstReqNo Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->trbpkbrequest_model->getRules("EDIT", $fstReqNo));
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
			"fstReqNo" => $fstReqNo,
			"fdtReqDate"=>dBDateFormat($this->input->post("fdtReqDate")),
            "finTrxId" => $this->input->post("hfinTrxId"),
			"fstDealerCode" => $this->input->post("fstDealerCode"),
            "finTransferType" => $this->input->post("finTransferType"),
            "fstMemo" => $this->input->post("fstMemo"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$this->trbpkbrequest_model->update($data);

        //Save Detail Request
        $this->load->model("trbpkbrequest_detail_model");
        $this->trbpkbrequest_detail_model->deleteByHeaderId($fstReqNo);
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $detail) {
            $data = [
                "fstReqNo" => $fstReqNo,
                "fstBpkbNo" => $detail->fstBpkbNo,
                "fstNotes" => $detail->fstNotes,
            ];
            $this->trbpkbrequest_detail_model->insert($data);
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
		$this->ajxResp["data"]["insert_id"] = $fstReqNo;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
        $this->datatables->setTableName("(
            SELECT a.*,b.fstDealerName FROM trbpkbrequest a LEFT JOIN tbdealers b ON a.fstDealerCode = b.fstDealerCode
        ) a");

		$selectFields = "fstReqNo,fdtReqDate,fstDealerName,finTransferType,fstMemo,fdtTrxPICApprovedDatetime,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
            if ($data["fdtTrxPICApprovedDatetime"] != null || $data["fdtTrxPICApprovedDatetime"] != ""){
                $fdtTrxPICApprovedDatetime = strtotime($data["fdtTrxPICApprovedDatetime"]);
                $data["fdtTrxPICApprovedDatetime"] = date("d-M-Y",$fdtTrxPICApprovedDatetime);
            }else{
                $data["fdtTrxPICApprovedDatetime"] = "";
            }
			$fdtReqDate = strtotime($data["fdtReqDate"]);
			$data["fdtReqDate"] = date("d-M-Y",$fdtReqDate);
		

			//action
			$data["action"]	= "<div style='font-size:16px'>
				<a class='btn-delete' href='#' data-id='" . $data["fstReqNo"] . "'><i class='fa fa-trash'></i></a>
			</div>";

			$arrDataFormated[] = $data;
		}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fstReqNo)
	{
		$this->load->model('trbpkbrequest_model');
		$data = $this->trbpkbrequest_model->getDataById($fstReqNo);
		$this->json_output($data);
	}

	public function delete($fstReqNo){
		parent::delete($fstReqNo);
		$this->load->model('trbpkbrequest_model');
        $this->load->model("trbpkbrequest_detail_model");
        $data = $this->trbpkbrequest_model->getDataById($fstReqNo);
		$approved = $data["request"];
        if($approved->fstTrxPICApprovedBy =="" || $approved->fstTrxPICApprovedBy == null ){
            $this->db->trans_start();
            $this->trbpkbrequest_model->delete($fstReqNo);
            $this->trbpkbrequest_detail_model->deleteByHeaderId($fstReqNo);
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
		$this->load->model('trbpkbrequest_model');
		$result = $this->trbpkbrequest_model->getAllList();
		$this->ajxResp["data"] = $result;
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
        $fstDealerCode = $this->input->post("fstDealerCode");
        $fstBpkbNo = $this->input->post("fstBpkbNo");

        $this->load->model('Trbpkbrequest_model');
		$data = $this->Trbpkbrequest_model->cekDealer($fstBpkbNo,$fstDealerCode);
		$dealer = $data["dealerbpkb"];
		if (!$dealer) {
            $this->ajxResp["status"] = "NOT VALID";
            $this->ajxResp["message"] = "Dealer tidak sesuai !!!";
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

}
