<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AmsApi extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		$this->load->model('msbranches_model');
		
	}    

    public function get_branch_info(){
        // $this->load->model("msbranches_model");
		$branchCode = $this->input->get('branch_code');
		$token = $this->input->get('token');
        $requestTime = $this->input->get('request_time');
        $string2token = $branchCode . date('ydm') . 'T' . $requestTime;
        $this_token = md5($string2token);
        
        if ($token == $this_token) {
            $branchData = $this->msbranches_model->getDataBranch($branchCode);
            $result = [
                "status"=>"OK",
                "message"=>"OK",
                "data"=>$branchData
            ];    
        } else {
            $result = [
                "status"=>"404",
                "message"=>"Invalid Authorization",
                "data"=>""
            ];    
        }

		header("Content-Type: application/json");
        echo json_encode($result);
		
	}

    public function addlog_startdownload()
	{
        // insert download log
        
		$this->load->model('Trlog_branch_model');
		
		$branchCode = $this->input->get('branch_code');
		$token = $this->input->get('token');
        $requestTime = $this->input->get('request_time');
        
        $startDatetime =  date('y/m/d', strtotime($this->input->get('start_datetime')));
        $datarangeStart = date('y/m/d', strtotime($this->input->get('datarange_startdate')));
        $datarangeEnd = date('y/m/d', strtotime($this->input->get('datarange_enddate')));
        
        $string2token = $branchCode . date('ydm') . 'T' . $requestTime;
        
        $this_token = md5($string2token);
        // echo json_encode($datarangeEnd);

        if ($token == $this_token) {
            $ssql = "SELECT * FROM trbranchlog WHERE fst_status ='ON_PROGRESS' AND fst_branch_code = ?";
            $qr = $this->db->query($ssql,[$branchCode]);
            //echo $this->db->last_query();
            //die();
            $rw = $qr->row();
            if($qr->row() != null){
                $datarange_startdate = date('y/m/d', strtotime($rw->fdt_datarange_start_date));
                $datarange_enddate = date('y/m/d', strtotime($rw->fdt_datarange_end_date));
                // echo($datarangeStart);
                // echo($datarange_startdate);
                // die();
                //jika ketemu dan datarange sama
                if($datarangeStart == $datarange_startdate && $datarangeEnd == $datarange_enddate){
                    $result = [
                        "status"=>"ERR",
                        "message"=>"On_Progress",
                        "data"=>""
                    ];
                }else{
                    $result = [
                        "status"=>"ERR",
                        "message"=>"Other On_Progress",
                        "data"=>[
                            "datarange_startdate"=>$datarange_startdate,
                            "datarange_enddate"=>$datarange_enddate
                        ]
                    ];
                }

            }else{

                $data = [
                    "fst_branch_code" => $branchCode,
                    "fdt_start_datetime" => $startDatetime,                
                    "fdt_datarange_start_date" => $datarangeStart,
                    "fdt_datarange_end_date" => $datarangeEnd,
                    "fst_status" => 'ON_PROGRESS',
                    "fst_info" => ''
                ];
    
                $this->db->trans_start();
                $insertId = $this->Trlog_branch_model->insert($data);
                $dbError  = $this->db->error();
                if ($dbError["code"] != 0) {
                    $result = [
                        "status"=>"DB_FAILED",
                        "message"=>"Insert Failed",
                        "data"=> $this->db->error()
                    ];
        
                    $this->db->trans_rollback();
                    header("Content-Type: application/json");
                    echo json_encode($result);
                            
                }
    
                $this->db->trans_complete();
    
                $result = [
                    "status"=>"OK",
                    "message"=>"Data saved",
                    "data"=> ""
                ];

            }

        }
        else {
            $result = [
                "status"=>"404",
                "message"=>"Authorization failed",
                "data"=> ""
            ];


        }
        header("Content-Type: application/json");
        echo json_encode($result);
	} 

    public function updatelog_enddownload()
    {
        $this->load->model('Trlog_branch_model');

        		
		$branchCode = $this->input->get('branch_code');
		$token = $this->input->get('token');
        $requestTime = $this->input->get('request_time');
        
        $endDatetime =  date('y/m/d', strtotime($this->input->get('enddate')));
        $datarangeStart = date('y/m/d', strtotime($this->input->get('datarange_startdate')));
        $datarangeEnd = date('y/m/d', strtotime($this->input->get('datarange_enddate')));

        $status = $this->input->get('status');
        $info = $this->input->get('info');
        
        $string2token = $branchCode . date('ydm') . 'T' . $requestTime;
        
        $this_token = md5($string2token);
        // echo json_encode($datarangeEnd);
        if ($token == $this_token) {
            $ssql = "SELECT * FROM trbranchlog WHERE fst_status ='ON_PROGRESS' AND fst_branch_code = ? AND DATE(fdt_datarange_start_date) = ? AND DATE(fdt_datarange_end_date) = ? ";
            $qr = $this->db->query($ssql,[$branchCode,$datarangeStart,$datarangeEnd]);
            //echo $this->db->last_query();
            //die();
            $rw = $qr->row();
            if($qr->row() != null){
                $log_id = $rw->fin_id;
                /*$data  = array(
                    'fdt_end_datetime' => $endDatetime,
                    'fst_status' => $status,
                    'fst_info' => $info
                );
                $this->db->where('fin_id', $log_id);
                $this->db->update('trlog_branch_model', $data);*/
                $ssql = "UPDATE trbranchlog SET fdt_end_datetime = '$endDatetime',fst_status='$status',fst_info='$info' WHERE fin_id = ?";
                $this->db->query($ssql,[$log_id]);
                $result = [
                    "status"=>"OK",
                    "message"=>"SUCCESS",
                    "data"=>[
                        "Status"=>$status,
                        "Info"=>$info,
                        "End Date"=>$endDatetime
                    ]
                ]; 
            }else{
                $result = [
                    "status"=>"ERR",
                    "message"=>"LOG NOT FOUND",
                    "data"=>''
                ]; 
            }
   
        } else {
            $result = [
                "status"=>"404",
                "message"=>"Invalid Authorization",
                "data"=>""
            ];    
        }
            header("Content-Type: application/json");
            echo json_encode($result);
    }

    public function encrypt() {
        $string = $this->input->get('msg');

        echo json_encode(urlencode(encrypString($string)));
    }  
    public function decrypt() {
        $string = $this->input->get('msg');
        // echo json_encode($string);
        echo json_encode(decryptString($string));
    } 
    
    
}