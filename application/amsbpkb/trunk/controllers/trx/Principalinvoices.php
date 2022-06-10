<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Principalinvoices extends MY_Controller
{
	public $menuName="principalinvoices"; 
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
		$this->list['page_name'] = "Principal Invoice / Faktur Kuning";
		$this->list['list_name'] = "Principal Invoice / Faktur Kuning List";
		$this->list['addnew_ajax_url'] = site_url() . 'trx/principalinvoices/add';
		//$this->list['report_url'] = site_url() . 'report/principalinvoices';
		$this->list['pKey'] = "finId";
        $this->list['trx_key'] = "finSalesTrxId";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'trx/principalinvoices/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'trx/principalinvoices/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'trx/principalinvoices/edit/';
        $this->list['checkin_ajax_url'] = site_url() . 'trx/principalinvoices/checkin_trx/';
		$this->list['arrSearch'] = [
			'fstDealerCode' => 'Dealer Code',
			'fstSPKNo' => 'SPK No',
            'fstCustomerName' => 'Customer Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Transaction', 'link' => '#', 'icon' => ''],
			['title' => 'Principal Invoice', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
            ['title' => 'ID', 'width' => '0%','visible'=>'false', 'data' => 'finId'],
			['title' => 'Dealer', 'width' => '10%', 'data' => 'fstDealerCode'],
			['title' => 'SPK No', 'width' => '10%', 'data' => 'fstSPKNo'],
            ['title' => 'Sales Date', 'width' => '10%', 'data' => 'fdtSalesDate'],
			['title' => 'NIK', 'width' => '10%', 'data' => 'fstNik'],
			['title' => 'Customer', 'width' => '15%', 'data' => 'fstCustomerName'],
			['title' => 'Leasing', 'width' => '10%', 'data' => 'fstLeasingCode'],
            ['title' => 'Engine No', 'width' => '10%', 'data' => 'fstEngineNo'],
            ['title' => 'Chasis No', 'width' => '10%', 'data' => 'fstChasisNo'],
            ['title' => 'Brand Type', 'width' => '10%', 'data' => 'fstBrandName'],
            ['title' => 'Colour', 'width' => '5%', 'data' => 'fstColourName'],
            ['title' => 'Checkin', 'width' => '10%', 'data' => 'fdtCheckinDate'],
            ['title' => 'Status', 'width' => '5%', 'data' => 'fstPrincipalInvoiceStatus'],
			['title' => 'Action', 'width' => '15%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
		];

        $mdlSalesTrx = $this->parser->parse('template/mdlSalesTrx', $this->list, true);
		$this->list['mdlSalesTrx'] = $mdlSalesTrx;
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = $this->parser->parse('pages/tr/principalinvoice/list', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = null;
		$this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
		$this->data['MAIN_HEADER'] = $main_header;
		$this->data['MAIN_SIDEBAR'] = $main_sidebar;
		$this->data['PAGE_CONTENT'] = $page_content;
		$this->data['MAIN_FOOTER'] = $main_footer;
		$this->parser->parse('template/main', $this->data);
	}

	public function openForm($mode = "ADD", $finId = 0)
	{
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Principal Invoice" : "Update Principal Invoice";
		$data["finId"] = $finId;

		$page_content = $this->parser->parse('pages/trx/principalinvoices/form', $data, true);
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

	public function edit($finId)
	{
		parent::edit($finId);
		$this->openForm("EDIT", $finId);
	}

	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('trprincipalinvoice_model');
		$this->form_validation->set_rules($this->trprincipalinvoice_model->getRules("ADD", ""));
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
		$insertId = $this->trprincipalinvoice_model->insert($data);
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

	public function checkin_trx($finSalesTrxId)
	{
		parent::ajx_edit_save();
		$this->load->model('trsalestrx_model');
        $this->load->model('trprincipalinvoice_model');
		//$finId = $this->input->post('finId');
		$data = $this->trsalestrx_model->getDataById($finSalesTrxId);
		$salestrx = $data["salestrx"];
		if (!$salestrx) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $finSalesTrxId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		/*$this->form_validation->set_rules($this->msbpkbwarehouse_model->getRules("EDIT", $finId));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}*/
		$checkin = $this->trprincipalinvoice_model->getCheckin($finSalesTrxId);
		$invoicecheckin = $checkin["invoicecheckin"];
        if(!$invoicecheckin){
            $data = [
                "finSalesTrxId" => $salestrx->finSalesTrxId,
                "fdtCheckinDate" => date("Y-m-d H:i:s"),
                "fstPrincipalInvoiceStatus" => 'CHECKIN',
                "fst_active" => 'A'
            ];
        }else{
            $data = [
                "finSalesTrxId" => $salestrx->finSalesTrxId,
                "fdtCheckinDate" => date("Y-m-d H:i:s"),
                "fstPrincipalInvoiceStatus" => 'CLAIM',
                "fst_active" => 'A'
            ];
        }


		$this->db->trans_start();

		$this->trprincipalinvoice_model->insert($data);
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
        if(!$invoicecheckin){
		    $this->ajxResp["message"] = "Faktur Kuning CHECKIN !";
        }else{
            $this->ajxResp["message"] = "Faktur Kuning CLAIM !";
        }
		$this->ajxResp["data"]["insert_id"] = $finSalesTrxId;
		$this->json_output();
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
        $this->datatables->setTableName("(
            SELECT a.*,b.fstDealerCode,b.fstSPKNo,b.fdtSalesDate,b.fstCustomerName,b.fstNik,b.fstLeasingCode,b.finBrandTypeId,b.fstColourCode,b.fstEngineNo,b.fstChasisNo,b.fstSalesName,c.fstBrandName,d.fstColourName
            FROM trprincipalinvoices a 
            LEFT JOIN trsalestrx b ON a.finSalesTrxId = b.finSalesTrxId
            LEFT JOIN tbbrandtypes c ON b.finBrandTypeId = c.finBrandTypeId
            LEFT JOIN tbcolours d ON b.fstColourCode = d.fstColourCode
            ORDER BY fdtCheckinDate DESC
        ) a");

		$selectFields = "finId,fstDealerCode,fstSPKNo,fdtSalesDate,fstNik,fstCustomerName,fstLeasingCode,fstEngineNo,fstChasisNo,fstBrandName,fstColourName,fdtCheckinDate,fstPrincipalInvoiceStatus,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$fdtSalesDate = strtotime($data["fdtSalesDate"]);
			$data["fdtSalesDate"] = date("d-M-Y",$fdtSalesDate);
			//action
			$data["action"]	= "<div style='font-size:16px'>
				<a class='btn-delete' href='#' data-id='" . $data["finId"] . "'><i class='fa fa-trash'></i></a>
			</div>";

			$arrDataFormated[] = $data;
		}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($finId)
	{
		$this->load->model('trprincipalinvoice_model');
		$data = $this->trprincipalinvoice_model->getDataById($finId);

		$this->json_output($data);
	}

	public function delete($id){
		parent::delete($id);
		$this->load->model('trprincipalinvoice_model');
		$this->db->trans_start();
        $this->trprincipalinvoice_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$this->load->model('trprincipalinvoice_model');
		$result = $this->trprincipalinvoice_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}

    public function ajxSalesTrxData(){
		/*$fstCustomerName = $this->input->post("fstCustomerName");
		$fstNik = $this->input->post("fstNik");
        "fstSPKNo":$("#fstSPKNo").val(),
        "fstBrandName":$("#fstBrandName").val(),
        "fstEngineNo":$("#fstEngineNo").val(),
        "fstChasisNo":$("#fstChasisNo").val(),*/

        $customer_name = $this->input->post("fstCustomerName");
        $nik = $this->input->post("fstNik");
        $spk_no = $this->input->post("fstSPKNo");
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
        if ($spk_no != "") {
            $swhere .= " and a.fstSPKNo = " . $this->db->escape($spk_no);
        }
        if ($brand_name != "") {
            $swhere .= " and a.fstBrandName = " . $this->db->escape($brand_name);
        }
        if ($engine_no != "") {
            $swhere .= " and a.fstEngineNo = " . $this->db->escape($engine_no);
        }
        if ($chasis_no != "") {
            $swhere .= " and a.fstChasisNo = " . $this->db->escape($chasis_no);
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
            $ssql = "SELECT a.*,b.fdtCheckinDate,b.fstPrincipalInvoiceStatus FROM trsalestrx a
            LEFT JOIN trprincipalinvoices b on a.finSalesTrxId = b.finSalesTrxId $swhere GROUP BY a.finSalesTrxId";
            $qr = $this->db->query($ssql);
            //echo $this->db->last_query();
            //die();
            $rs = $qr->result();
        }



		$result = [
			"status"=>"SUCCESS",
			"data"=>$rs
		];

		header('Content-Type: application/json');
		echo json_encode($result);

	}

}
