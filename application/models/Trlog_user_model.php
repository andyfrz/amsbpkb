<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Trlog_user_model extends MY_MODEL {

    public $tableName = "truserlog";
    public $pkey = "fin_id";

    public function __construct() {
        parent::__construct();
    }

    public function deleteByUser($fst_user_code){
        $ssql = "delete from " . $this->tableName . " where fst_user_code = ?";
        $this->db->query($ssql,[$fst_user_code]);
    }
    public function log_user($type,$username){
        					
        $data = [
            "fst_user_code" => $username,
            "fdt_log_datetime" => date("Y-m-d H:i:s"),
            "fst_log_info" => $type,
            "fin_insert_id" => $username,
            "fdt_insert_datetime" => date("Y-m-d H:i:s")
        ];
        $this->db->insert("truserlog",$data);

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