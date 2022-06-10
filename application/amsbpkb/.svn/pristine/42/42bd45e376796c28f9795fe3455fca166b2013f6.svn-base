<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Branch_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'KODE', 'NAMA'];

    public function queryComplete($data, $sorder_by="fst_branch_code", $rptLayout="1") {
    

        
        $swhere = "";
        $sorderby = "";

        if ($swhere != "") {
            $swhere = " where " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " order by " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT * FROM tbbranch" . $swhere . $sorderby;
                break;
            default:
                break;
        }

        $query = $this->db->query($ssql);
        //echo $this->db->last_query();
        //die();
        return $query->result();
    }

    public function getRules()
    {
        $rules = [];
        
        return $rules;
    } 

    public function processReport($data) {
        // var_dump($data);die();
        $dataReport = $this->queryComplete($data,"","1");
        // var_dump($recordset);
        // print_r($dataReturn["fields"]);die();
        
        $reportData = $this->parser->parse('reports/branch/rpt',["rows"=>$dataReport['rows']], true);
        // var_dump($reportData);die();
        // return $reportData;
        return $reportData;
        
    }

}