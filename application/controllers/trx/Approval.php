<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Approval extends MY_Controller{

	public function __construct(){
		parent::__construct();
		
	}
	public function index(){
		$this->load->library("menus");		
        
        $main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);		
		$data["title"] = lang("Approval");
		
		$page_content = $this->parser->parse('pages/tr/approval', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
    }

    public function fetch_need_approval_list(){
		$this->load->library("datatables");
        $user = $this->aauth->user();
        $userActive = $user->fstUserCode;

		$dateRange = parseDateRange($this->input->get('dateRange'));
		/*$optionModule = $this->input->get("optionModule");
		if($optionModule == "" || $optionModule == null){
			$optionModule ="ALL";
		}*/
        $this->datatables->setTableName(
            "(SELECT a.*,b.fstUserCode,c.fstDealerName FROM trbpkbrequest a 
            LEFT JOIN tbbpkbtrxpicdetails b ON a.finTrxId = b.finTrxId 
            LEFT JOIN tbdealers c ON a.fstDealerCode = c.fstDealerCode
            WHERE b.fstUserCode = '$userActive'
            AND a.fstTrxPICApprovedBy IS NULL 
            AND a.fdtTrxPICApprovedDatetime IS NULL
            AND a.fdtReqDate >= '$dateRange[from]' AND a.fdtReqDate <= '$dateRange[to]') a"
        );


		$selectFields = "a.fstReqNo,a.fdtReqDate,a.fstDealerName,a.finTransferType,a.fstTrxPICApprovedBy,a.fdtTrxPICApprovedDatetime";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "a.fst_active !='D'";
		
		// Format Data
		$datasources = $this->datatables->getData();
		//echo $this->db->last_query();
		//echo $this->db->last_query();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
            if ($data["fdtTrxPICApprovedDatetime"] != null){
                $ApprovedDate = strtotime($data["fdtTrxPICApprovedDatetime"]);						
                $data["fdtTrxPICApprovedDatetime"] = date("d-M-Y H:i:s",$ApprovedDate);
               
            }
            switch($data["finTransferType"]){
                case "2":
                    $data["finTransferType"] = "Request Checkout";
                    break;				
                case "3":
                    $data["finTransferType"] = "Request Dealer";
                    break;
                case "4":
                    $data["finTransferType"] = "Peminjaman";
                    break;
                case "5":
                    $data["finTransferType"] = "Perbaikan";
                    break;
                case "6":
                    $data["finTransferType"] = "Proses Ulang";
                    break;
                default:
                    $data["finTransferType"] = "Request Checkout";
            };
            $ReqDate = strtotime($data["fdtReqDate"]);						
            $data["fdtReqDate"] = date("d-M-Y",$ReqDate);
            $arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}
	
	public function fetch_hist_approval_list(){
		$this->load->library("datatables");
		
        $user = $this->aauth->user();
        $userActive = $user->fstUserCode;

		$dateRange = parseDateRange($this->input->get('dateRange'));
		/*$optionModule = $this->input->get("optionModule");
		if($optionModule == "" || $optionModule == null){
			$optionModule ="ALL";
		}*/
        $this->datatables->setTableName(
            "(SELECT a.*,b.fstUserCode,c.fstDealerName FROM trbpkbrequest a 
            LEFT JOIN tbbpkbtrxpicdetails b ON a.finTrxId = b.finTrxId 
            LEFT JOIN tbdealers c ON a.fstDealerCode = c.fstDealerCode
            WHERE b.fstUserCode = '$userActive'
            AND a.fstTrxPICApprovedBy IS NOT NULL 
            AND a.fdtTrxPICApprovedDatetime IS NOT NULL
            AND a.fdtReqDate >= '$dateRange[from]' AND a.fdtReqDate <= '$dateRange[to]') a"
        );

		$selectFields = "a.fstReqNo,a.fdtReqDate,a.fstDealerName,a.finTransferType,a.fstTrxPICApprovedBy,a.fdtTrxPICApprovedDatetime";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "a.fst_active !='D'";
		
		// Format Data
		$datasources = $this->datatables->getData();
		//echo $this->db->last_query();
		//echo $this->db->last_query();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
            if ($data["fdtTrxPICApprovedDatetime"] != null){
                $ApprovedDate = strtotime($data["fdtTrxPICApprovedDatetime"]);						
                $data["fdtTrxPICApprovedDatetime"] = date("d-M-Y H:i:s",$ApprovedDate);
            }
            switch($data["finTransferType"]){
                case "2":
                    $data["finTransferType"] = "Request Checkout";
                    break;				
                case "3":
                    $data["finTransferType"] = "Request Dealer";
                    break;
                case "4":
                    $data["finTransferType"] = "Peminjaman";
                    break;
                case "5":
                    $data["finTransferType"] = "Perbaikan";
                    break;
                case "6":
                    $data["finTransferType"] = "Proses Ulang";
                    break;
                default:
                    $data["finTransferType"] = "Request Checkout";
            };
            $ReqDate = strtotime($data["fdtReqDate"]);						
            $data["fdtReqDate"] = date("d-M-Y",$ReqDate);
            $arrDataFormated[] = $data;

		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
    }
    
    public function doApproval(){
        $this->load->model('trbpkbrequest_model');
        $fstReqNo = $this->input->post("fstReqNo");
        try{
		
			$this->db->trans_start();		
			$this->trbpkbrequest_model->approve($fstReqNo);		
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

	public function cancelApproval(){
		$this->load->model('trbpkbrequest_model');
		$fstReqNo = $this->input->post("fstReqNo");

		try{
		
			$this->db->trans_start();		
			$this->trbpkbrequest_model->cancelApprove($fstReqNo);		
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
	
	public function doReject($finRecId){
        $this->load->model('trverification_model');

		try{
			$this->db->trans_start();
			$result = $this->trverification_model->reject($finRecId);
			$this->db->trans_complete();
			
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "";
			$this->ajxResp["data"]=[];
			$this->json_output();
			
		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
		}
	}
	
	public function viewDetail($finRecId){
		$this->load->model('trverification_model');
		$this->trverification_model->showTransaction($finRecId);
	}


	
}