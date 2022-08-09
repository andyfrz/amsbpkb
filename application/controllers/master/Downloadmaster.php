<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Downloadmaster extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->aauth->is_permit("download_master")){
            show_404();
		}
        $this->load->library('form_validation');
        //$this->load->model('ticketstatus_model');
    }

    public function index(){

		$this->load->library('menus');
        $this->list['page_name']="Download Master";
        $this->list['pKey']="id";
        $this->list['arrSearch']=[];
		
		$this->list['breadcrumbs']=[
			['title'=>'Master','link'=>'#','icon'=>"<i class='fa fa-cog'></i>"],
			['title'=>'Download','link'=>'#','icon'=>''],
			['title'=>'','link'=> NULL ,'icon'=>''],
		];
		
		$this->list['columns']=[];
        $main_header = $this->parser->parse('inc/main_header',[],true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
        $page_content = $this->parser->parse('pages/master/download/form',$this->list,true);
        $main_footer = $this->parser->parse('inc/main_footer',[],true);
        $control_sidebar = "";
        $control_sidebar = null;
        $this->data['ACCESS_RIGHT']="A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR']= $main_sidebar;
        $this->data['PAGE_CONTENT']= $page_content;
        $this->data['MAIN_FOOTER']= $main_footer;        
        $this->parser->parse('template/main',$this->data);
		
    }

    function download_colours(){
		$date = date("ydm");
		$time = date("Hi");
		$token = md5($date.'T'.$time);
		/*$data = array(
			'startDateString' => '20220601',
			'endDateString' => '20220610',
			'token' => $token ,
			'request_time' => $time
		);*/
		$data = array(
			'token' => $token ,
			'request_time' => $time
		);
		
		$endpoint = "http://36.94.119.139:5100/api/bpkb/getColourDataList";
		
		//$endpoint = "http://36.94.119.139:5100/api/bpkb/getAccountDataList";
		$url = $endpoint . '?' . http_build_query($data);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$resp = curl_exec($curl);
		curl_close($curl);

        $this->load->model('Mscolours_model');;
		//$data = file_get_contents($resp);
		$this->db->trans_start();
		$details = json_decode($resp, true);
		$rec = 0;
		foreach ($details as $detail=>$item) {
			if (!$this->Mscolours_model->isExist($item['ColourCode']) ){
				$rec++;
				$data = [
					"fstColourCode" => $item['ColourCode'],
					"fstColourName" => $item['ColourName'],
					"fst_active" =>'A'
				];
				$this->Mscolours_model->insert($data);
			};
			
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
		$this->ajxResp["message"] = "$rec data insert success";
		$this->ajxResp["data"]["insert_id"] = 'admin';
		$this->json_output();
        //redirect('trx/salestrx');	
	}

	function download_dealers(){
		$date = date("ydm");
		$time = date("Hi");
		$token = md5($date.'T'.$time);
		/*$data = array(
			'startDateString' => '20220601',
			'endDateString' => '20220610',
			'token' => $token ,
			'request_time' => $time
		);*/
		$data = array(
			'token' => $token ,
			'request_time' => $time
		);
		
		$endpoint = "http://36.94.119.139:5100/api/bpkb/getDealerDatalist";
		
		//$endpoint = "http://36.94.119.139:5100/api/bpkb/getAccountDataList";
		$url = $endpoint . '?' . http_build_query($data);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$resp = curl_exec($curl);
		curl_close($curl);

        $this->load->model('Msdealers_model');;
		//$data = file_get_contents($resp);
		$this->db->trans_start();
		$details = json_decode($resp, true);
		$rec = 0;
		foreach ($details as $detail=>$item) {
			if (!$this->Msdealers_model->isExist($item['DealerCode']) ){
				$rec++;
				$data = [
					"fstDealerCode" => $item['DealerCode'],
					"fstDealerName" => $item['DealerName'],
					"fstPersonInCharge" => $item['PICGeneral'],
					"fstPhoneNo" => $item['Phone1'],
					"fstAddress" => $item['Address1'],
					"fstEmail" => $item['Email'],
					"fst_active" =>'A'
				];
				$this->Msdealers_model->insert($data);
			};

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
		$this->ajxResp["message"] = "$rec data insert success";
		$this->ajxResp["data"]["insert_id"] = 'admin';
		$this->json_output();
        //redirect('trx/salestrx');	
	}

	function download_brands(){
		$date = date("ydm");
		$time = date("Hi");
		$token = md5($date.'T'.$time);

		$data = array(
			'token' => $token ,
			'request_time' => $time
		);
		
		$endpoint = "http://36.94.119.139:5100/api/bpkb/getTMDataList";
		
		$url = $endpoint . '?' . http_build_query($data);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$resp = curl_exec($curl);
		curl_close($curl);

		/*echo "<pre>";
		print_r($details);
		echo "</pre>";*/

        $this->load->model('Msbrands_model');;
		//$data = file_get_contents($resp);
		$this->db->trans_start();
		$details = json_decode($resp, true);
		$rec = 0;
		foreach ($details as $detail=>$item) {
			if (!$this->Msbrands_model->isExist($item['TrademarkCode']) ){
				$rec++;
				$dataInsert = [
					"fstBrandCode" => $item['TrademarkCode'],
					"fstBrandName" => $item['TrademarkName'],
					"fstGenesysBrandCode" => $item['TrademarkCode'],
					"fst_active" =>'A'
				];
				$this->Msbrands_model->insert($dataInsert);
			}
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
		$this->ajxResp["message"] = "$rec data insert success";
		$this->ajxResp["data"]["insert_id"] = 'admin';
		$this->json_output();
        //redirect('trx/salestrx');	
	}

	function download_brandtypes(){
		$date = date("ydm");
		$time = date("Hi");
		$token = md5($date.'T'.$time);

		$data = array(
			'token' => $token ,
			'request_time' => $time
		);
		
		$endpoint = "http://36.94.119.139:5100/api/bpkb/getTMTypeDataList";
		
		$url = $endpoint . '?' . http_build_query($data);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$resp = curl_exec($curl);
		curl_close($curl);

		/*echo "<pre>";
		print_r($details);
		echo "</pre>";*/

        $this->load->model('Msbrandtypes_model');;
		//$data = file_get_contents($resp);
		$this->db->trans_start();
		$details = json_decode($resp, true);
		/*echo "<pre>";
		print_r($details);
		echo "</pre>";*/
		$rec = 0;
		foreach ($details as $detail=>$item) {
			if (!$this->Msbrandtypes_model->isExist($item['TMTypeCode']) ){
				$rec++;
				$dataInsert = [
					"finBrandTypeId" => $item['TMTypeCode'],
					"fstBrandCode" => $item['TMCode'],
					"fstBrandName" => $item['TMTypeName'],
					"fstEnginePrefix" => $item['EnginePrefixNo'],
					"fstChassisPrefix" => $item['ChassisPrefixNo'],
					"fstGenesysBrandTypeCode" => $item['TMTypeCode'],
					"fst_active" =>'A'
				];
				$this->Msbrandtypes_model->insert($dataInsert);
			};

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
		$this->ajxResp["message"] = "$rec data insert success";
		$this->ajxResp["data"]["insert_id"] = 'admin';
		$this->json_output();
        //redirect('trx/salestrx');	
	}
}