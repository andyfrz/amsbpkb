<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{
	public $menuName="user"; 
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
		$this->list['page_name'] = "User";
		$this->list['list_name'] = "User List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/user/add';
		$this->list['report_url'] = site_url() . 'report/users';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/user/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/user/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/user/edit/';
		$this->list['arrSearch'] = [
			'fstUserCode' => 'User Code',
			'fstUserName' => 'User Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'User', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'User Code', 'width' => '10%', 'data' => 'fstUserCode'],
			['title' => 'User Name', 'width' => '13%', 'data' => 'fstUserName'],
			['title' => 'Email', 'width' => '15%', 'data' => 'fstEmail'],
			['title' => 'Mobile No', 'width' => '15%', 'data' => 'fstMobileNo'],
			['title' => 'Admin', 'width' => '15%', 'data' => 'fblIsAdmin'],
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

	public function openForm($mode = "ADD", $fstUserCode = "")
	{
		$this->load->library("menus");
		$this->load->model("usersgroup_model");
		$this->load->model("users_model");


		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add User" : "Update User";
		$data["fstUserCode"] = $fstUserCode;
		$data["mdlPrint"] = $mdlPrint;
		$data["arrUser_R"] = $this->users_model->getUserList_R();
		//$data["arrBranch"] = $this->msbranches_model->getAllList();
		//$data["arrGroup"] = $this->usersgroup_model->getAllList();
		//$data["finSalesDepartmentId"] = getDbConfig("sales_department_id");
		$activeUser = $this->session->userdata('active_user');
		$data["specialAccess"] = $activeUser->fblIsAdmin;

		$page_content = $this->parser->parse('pages/user/form', $data, true);
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

	public function edit($fstUserCode)
	{
		parent::edit($fstUserCode);
		$this->openForm("EDIT", $fstUserCode);
	}

	public function ajx_add_save()
	{
		parent::ajx_add_save();
		$this->load->model('users_model');
		$this->form_validation->set_rules($this->users_model->getRules("ADD", ""));
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
			"fstUserCode" => $this->input->post("fstUserCode"),
			"fstUserName" => $this->input->post("fstUserName"),
			"fstPassword" => md5("password"),
			"fstEmail" => $this->input->post("fstEmail"),
			"fstMobileNo" => $this->input->post("fstMobileNo"),
			"fblIsAdmin" => $this->input->post("fblIsAdmin") == null? 0:1,
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->users_model->insert($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}


		//Save User Detail

		/*$this->load->model("User_detail_model");
		$details = $this->input->post("detailuser");
		$details = json_decode($details);
		foreach ($details as $detailuser) {
			$data = [
				"fst_user_code" => $this->input->post("fst_user_code"),
				"fst_branch_code" => $detailuser->fst_branch_code
			];
			$this->User_detail_model->insert($data);
			$dbError  = $this->db->error();
			if ($dbError["code"] != 0) {
				$this->ajxResp["status"] = "DB_FAILED";
				$this->ajxResp["message"] = "Insert Detail Failed";
				$this->ajxResp["data"] = $this->db->error();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}
		}*/

		//Save File
		/*if (!empty($_FILES['fst_avatar']['tmp_name'])) {
			$config['upload_path']          = './assets/app/users/avatar';
			$config['file_name']			= 'avatar_' . $insertId . '.jpg';
			$config['overwrite']			= TRUE;
			$config['file_ext_tolower']		= TRUE;
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0; //kilobyte
			$config['max_width']            = 0; //1024; //pixel
			$config['max_height']           = 0; //768; //pixel

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('fst_avatar')) {
				$this->ajxResp["status"] = "IMAGES_FAILED";
				$this->ajxResp["message"] = "Failed to upload images, " . $this->upload->display_errors();
				$this->ajxResp["data"] = $this->upload->display_errors();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			} else {
				//$data = array('upload_data' => $this->upload->data());			
			}
			$this->ajxResp["data"]["data_image"] = $this->upload->data();
		}*/

		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function ajx_edit_save()
	{
		parent::ajx_edit_save();
		$this->load->model('users_model');
		$fstUserCode = $this->input->post('fstUserCode');
		$data = $this->users_model->getDataById($fstUserCode);
		$user = $data["user"];
		if (!$user) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fstUserCode Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->users_model->getRules("EDIT", $fstUserCode));
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
			"fstUserCode" => $fstUserCode,
			"fstUserName" => $this->input->post("fstUserName"),
			"fstEmail" => $this->input->post("fstEmail"),
			"fstMobileNo" => $this->input->post("fstMobileNo"),
			"fblIsAdmin" => $this->input->post("fblIsAdmin") == null? 0:1,
			"fst_active" => 'A'
		];

		$this->db->trans_start();

		$this->users_model->update($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		//Save User Detail

		/*$this->load->model("user_detail_model");
		$this->user_detail_model->deleteByHeaderId($fst_user_code);
		$details = $this->input->post("detailuser");
		$details = json_decode($details);
		foreach ($details as $detailuser) {
			$data = [
				"fst_user_code" => $fst_user_code,
				"fst_branch_code" => $detailuser->fst_branch_code
			];
			$this->user_detail_model->insert($data);
			$dbError  = $this->db->error();
			if ($dbError["code"] != 0) {
				$this->ajxResp["status"] = "DB_FAILED";
				$this->ajxResp["message"] = "Insert Detail Failed";
				$this->ajxResp["data"] = $this->db->error();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}
		}*/


		//Save File
		/*if (!empty($_FILES['fst_avatar']['tmp_name'])) {
			$config['upload_path']          = './assets/app/users/avatar';
			$config['file_name']			= 'avatar_' . $fst_user_code . '.jpg';
			$config['overwrite']			= TRUE;
			$config['file_ext_tolower']		= TRUE;
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0; //kilobyte
			$config['max_width']            = 0; //1024; //pixel
			$config['max_height']           = 0; //768; //pixel

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('fst_avatar')) {
				$this->ajxResp["status"] = "IMAGES_FAILED";
				$this->ajxResp["message"] = "Failed to upload images, " . $this->upload->display_errors();
				$this->ajxResp["data"] = $this->upload->display_errors();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			} else {
				//$data = array('upload_data' => $this->upload->data());			
			}
			$this->ajxResp["data"]["data_image"] = $this->upload->data();
		}*/

		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $fstUserCode;
		$this->json_output();
	}

	public function remove_add_save()
	{
		$this->load->model('users_model');
		$data = [
			'fst_fullname' => $this->input->get("fst_fullname"),
			'fdt_insert_datetime' => 'sekarang'
		];
		if ($this->db->insert('users', $data)) {
			echo "insert success";
		} else {
			$error = $this->db->error();
			print_r($error);
		}
		die();

		echo "Table Name :" . $this->users_model->getTableName();
		print_r($this->users_model->getRules());

		$this->form_validation->set_rules($this->users_model->rules);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE) {
			echo form_error();
			//$this->add();
		} else {
			//$this->load->view('formsuccess');
			echo "Success";
		}

		//print_r($_POST);
		$config['allowed_types'] = 'gif|jpg|png'; //Images extensions accepted
		$config['max_size']    = '2048';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$config['overwrite'] = TRUE;

		$this->load->library('upload', $config);
		$upload_data = $this->upload->data("fst_avatar");

		print_r($upload_data);

		print_r($_FILES);
	}

	public function fetch_list_data()
	{
		$this->load->library("datatables");
		$this->datatables->setTableName("tbusers");

		$selectFields = "*";
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
				<a class='btn-edit' href='#' data-id='" . $data["fstUserCode"] . "'><i class='fa fa-pencil'></i></a>
			</div>";

			$arrDataFormated[] = $data;
		}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fstUserCode)
	{
		$this->load->model('users_model');
		$data = $this->users_model->getDataById($fstUserCode);

		$this->json_output($data);
	}

	public function delete($id){
		parent::delete($id);
		$this->load->model('users_model');
		$this->db->trans_start();
        $this->users_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$this->load->model('users_model');
		$result = $this->users_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}

	public function change_branch($active_branch_id)
	{
		$activeUser = $this->session->userdata('active_user');
		if ($activeUser->fbl_is_hq) {
			$this->session->set_userdata('active_branch_id', $active_branch_id);
		}
		$reqURL = $_SERVER["HTTP_REFERER"];
		redirect($reqURL);
	}

	public function changepassword(){
		$this->load->library("menus");

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["title"] = "Change password";
		//$data["fin_user_id"] = $fin_user_id;

		$page_content = $this->parser->parse('pages/user/changepassword', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}

	public function change_password(){

		$activeUser = $this->aauth->user();
		$fstUserCode = $activeUser->fstUserCode;
		//$this->load->model("trlog_user_model");

		$this->load->model('users_model');
		$data = $this->users_model->getDataById($fstUserCode);
		$user = $data["user"];

		$this->form_validation->set_rules($this->users_model->getRulesCp($fstUserCode));
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
			"fstUserCode" => $fstUserCode,
			"fstPassword" => md5($this->input->post("new_password1"))
		];
		$this->db->trans_start();

		$this->users_model->update($data);
		//$this->trlog_user_model->log_user($type = "change password",$fstUserCode);
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
		$this->ajxResp["message"] = "Password updated !";
		//$this->ajxResp["data"]["insert_id"] = $fin_user_id;
		$this->json_output();
		
	}

	public function get_user_pic()
    {
        $term = $this->input->get("term");
        $ssql = "select fstUserCode,fstUserName from tbusers where fstUserName like ? order by fstUserName";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        $this->json_output($rs);
    }

	public function update_token($new_token)
	{
		$this->session->set_userdata('refresh_token', $new_token);
		$reqURL = $_SERVER["HTTP_REFERER"];
		redirect($reqURL);
	}



	public function report_users()
	{
		$this->load->library('pdf');
		//$customPaper = array(0,0,381.89,595.28);
		//$this->pdf->setPaper($customPaper, 'landscape');
		//$this->pdf->setPaper('A4', 'portrait');
		$this->pdf->setPaper('A4', 'landscape');

		$this->load->model("users_model");
		$listUser = $this->users_model->get_Users();
		$data = [
			"datas" => $listUser
		];

		$this->pdf->load_view('report/users_pdf', $data);
		$this->Cell(30, 10, 'Percobaan Header Dan Footer With Page Number', 0, 0, 'C');
		$this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'R');
	}

	public function get_printUser($branchName,$departmentName,$userId_awal,$userId_akhir) {
        $layout = $this->input->post("layoutColumn");
        $arrLayout = json_decode($layout);
        
        $this->load->model("users_model");
        $this->load->library("phpspreadsheet");
        
        $spreadsheet = $this->phpspreadsheet->load(FCPATH . "assets/templates/unlock/template_users_log.xlsx");
        $sheet = $spreadsheet->getActiveSheet();
        
		$sheet->getPageSetup()->setFitToWidth(1);
		$sheet->getPageSetup()->setFitToHeight(0);
		$sheet->getPageMargins()->setTop(1);
		$sheet->getPageMargins()->setRight(0.5);
		$sheet->getPageMargins()->setLeft(0.5);
        $sheet->getPageMargins()->setBottom(1);

        //AUTO SIZE COLUMN
        $sheet->getColumnDimension("A")->setAutoSize(true);
        $sheet->getColumnDimension("B")->setAutoSize(true);
        $sheet->getColumnDimension("C")->setAutoSize(true);
        $sheet->getColumnDimension("D")->setAutoSize(true);
        $sheet->getColumnDimension("E")->setAutoSize(true);
        $sheet->getColumnDimension("F")->setAutoSize(true);
		$sheet->getColumnDimension("G")->setAutoSize(true);
		$sheet->getColumnDimension("H")->setAutoSize(true);
        $sheet->getColumnDimension("I")->setAutoSize(true);
        $sheet->getColumnDimension("J")->setAutoSize(true);
        $sheet->getColumnDimension("K")->setAutoSize(true);
        $sheet->getColumnDimension("L")->setAutoSize(true);
        $sheet->getColumnDimension("M")->setAutoSize(true);
		$sheet->getColumnDimension("N")->setAutoSize(true);

        // SUBTITLE
        $sheet->mergeCells('B4:D4');
        $sheet->mergeCells('B5:D5');
        $sheet->mergeCells('B3:D3');

        //HEADER COLUMN
        $sheet->setCellValue("A7", "No.");
        $sheet->setCellValue("B7", "ID");
        $sheet->setCellValue("C7", "User Name");
        $sheet->setCellValue("D7", "Full Name");
        $sheet->setCellValue("E7", "Gender");
        $sheet->setCellValue("F7", "Birth date");
		$sheet->setCellValue("G7", "Birth place");
		$sheet->setCellValue("H7", "Address");
		$sheet->setCellValue("I7", "Phone");
		$sheet->setCellValue("J7", "Email");
		$sheet->setCellValue("K7", "Branch Name");
		$sheet->setCellValue("L7", "Department");
		$sheet->setCellValue("M7", "Group");
		$sheet->setCellValue("N7", "Admin");
		$i = 13;
        $col = $this->phpspreadsheet->getNameFromNumber($i);
        //TITLE
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue("A1", "USER LIST");

        //FORMAT NUMBER
        //$spreadsheet->getActiveSheet()->getStyle('D8:'.$col.'500')->getNumberFormat()->setFormatCode('#,##0.00');
        
        //COLOR HEADER COLUMN
        $spreadsheet->getActiveSheet()->getStyle('A7:N7')
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('99FFFF');

        //FONT HEADER CENTER
        $spreadsheet->getActiveSheet()->getStyle('A7:N7')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        //FONT ITALIC
        $italycArray = [
            'font' => [
                'italic' => true,
            ],
        ];

        //FONT BOLD
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
        ];
        $sheet->getStyle('A7:N7')->applyFromArray($styleArray);
        $sheet->getStyle('B3:N3')->applyFromArray($styleArray);
        $sheet->getStyle('B4:N4')->applyFromArray($styleArray);
        $sheet->getStyle('B5:N5')->applyFromArray($styleArray);

        //FONT SIZE
        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setSize(18);
        $spreadsheet->getActiveSheet()->getStyle("A3:N5")->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A7:N7")->getFont()->setSize(12);

        $iRow1 = 4;
        $iRow2 = 5;
        $iRow = 8;
        $no = 1;

        //DATE & TIME
        $sheet->setCellValue('K3', '=NOW()');
        $sheet->mergeCells('K3:N3');
        $sheet->setCellValue('K4', '=NOW()');
        $sheet->mergeCells('K4:N4');

        $printUser = $this->users_model->getPrintUser($branchName,$departmentName,$userId_awal,$userId_akhir);
        foreach ($printUser as $rw) {
			$fbl_admin = $rw->fbl_admin;
            switch($fbl_admin){
				case 0:
                    $fbl_admin = "FALSE";
					break;
				case 1:
					$fbl_admin = "TRUE";
					break;
			}
			$sheet->setCellValue("A$iRow", $no++);
			$sheet->setCellValue("B$iRow1", $rw->fst_branch_name); //fin_item_id & fst_vendor_item_name
			$sheet->setCellValue("B$iRow2", $rw->fst_department_name); //fin_item_group_id & fst_item_group_name
			//$sheet->setCellValue("A$iRow", $no++);
			$sheet->setCellValue("B$iRow", $rw->fin_user_id);
			$sheet->setCellValue("C$iRow", $rw->fst_username);
			$sheet->setCellValue("D$iRow", $rw->fst_fullname);
			$sheet->setCellValue("E$iRow", $rw->fst_gender);
			$sheet->setCellValue("F$iRow", $rw->fdt_birthdate);
			$sheet->setCellValue("G$iRow", $rw->fst_birthplace);
			$sheet->setCellValue("H$iRow", $rw->fst_address);
			$sheet->setCellValue("I$iRow", $rw->fst_phone);
			$sheet->setCellValue("J$iRow", $rw->fst_email);
			$sheet->setCellValue("K$iRow", $rw->fst_branch_name);
			$sheet->setCellValue("L$iRow", $rw->fst_department_name);
			$sheet->setCellValue("M$iRow", $rw->fst_group_name);
			$sheet->setCellValue("N$iRow", $fbl_admin);
			if ($fbl_admin =="FALSE"){
				$spreadsheet->getActiveSheet()->getStyle("N$iRow")->getFont()->getColor()->setRGB('0000FF');
			}
            $iRow++;

            
        }

        //BORDER
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED
                ],
            ],
        ];
        $iRow = $iRow - 1;
        $sheet->getStyle('A7:'.$col.$iRow)->applyFromArray($styleArray);
        
        //FILE NAME WITH DATE
        $this->phpspreadsheet->save("userlist_report_" . date("Ymd") . ".xls" ,$spreadsheet);

    }
}
