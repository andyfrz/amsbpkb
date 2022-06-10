<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Trlog_branch_model extends MY_MODEL {

    public $tableName = "trbranchlog";
    public $pkey = "fin_id";

    public function __construct() {
        parent::__construct();
    }

    public $layout1Columns = ['No', 'KODE', 'NAMA'];

    public function queryComplete($data, $sorder_by="fst_branch_code", $rptLayout="1") {
    

        $user = $this->aauth->user();
        $user_active = $user->fst_user_code;
        $swhere = "";
        $sorderby = "";
        if ($user_active != "") {
            $swhere .= " and a.fst_user_code = " . $this->db->escape($user_active);
        }
        if ($swhere != "") {
            $swhere = " where " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " order by " .$sorder_by;
        }
        
  
        $ssql = "SELECT a.fst_user_code,a.fst_branch_code,b.fdt_start_datetime,b.fdt_end_datetime,b.fdt_datarange_start_date,b.fdt_datarange_end_date,b.fst_status,b.fst_info,c.fst_branch_name, 
        c.fst_branch_address,c.fst_phone,c.fst_email,c.fst_pic,c.fst_branchtemp_dbconnstring,c.fst_token FROM tbusersdetail a 
        LEFT JOIN (
            SELECT fst_branch_code,fdt_start_datetime,fdt_end_datetime,fdt_datarange_start_date,fdt_datarange_end_date,fst_status,fst_info 
            FROM trbranchlog
            INNER JOIN (
            SELECT fst_branch_code, MAX(fdt_datarange_end_date) AS fdt_datarange_end_date
            FROM trbranchlog GROUP BY fst_branch_code
            ) AS MAX USING (fst_branch_code, fdt_datarange_end_date)
            ) b on a.fst_branch_code = b.fst_branch_code 
        LEFT JOIN tbbranch c on a.fst_branch_code = c.fst_branch_code". $swhere . $sorderby;

        $query = $this->db->query($ssql);
        //echo $this->db->last_query();
        //die();
        return $query->result();
    }
}