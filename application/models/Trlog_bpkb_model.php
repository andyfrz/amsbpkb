<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Trlog_bpkb_model extends MY_MODEL {

    public $tableName = "trbpkblogs";
    public $pkey = "finId";

    public function __construct() {
        parent::__construct();
    }


    public function log_bpkb($info,$bpkbno){
        					
        $data = [
            "fstBpkbNo" => $bpkbno,
            "fdtTrxDate" => date("Y-m-d H:i:s"),
            "fstTrxInfo" => $info,
            "fin_insert_id" => $username,
            "fdt_insert_datetime" => date("Y-m-d H:i:s")
        ];
        $this->db->insert("trbpkblogs",$data);

        /*$user_status = $this->session->userdata("active_user");
        $data  = array(
            "fst_user_code" => $user_status,
            "fdt_log_datetime" => date("Y-m-d H:i:s"),
            "fst_log_info" => 'Login',
            "fin_insert_id" => $user_status,
            "fdt_insert_datetime" => date("Y-m-d H:i:s")
        );
        $this->db->insert('trlog_user_model', $data);
        $log_id = $this->db->insert_id();*/  
    }
}