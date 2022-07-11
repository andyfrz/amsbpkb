<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trbpkbrequest_detail_model extends MY_Model {
    public $tableName = "trbpkbrequestdetails";
    public $pkey = "finRecId";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        return $rules;
    }

    public function deleteByHeaderId($fstReqNo)
    {
        $ssql = "DELETE FROM " . $this->tableName . " WHERE fstReqNo = ?";
		$this->db->query($ssql,[$fstReqNo]);
    }

    public function cekbpkb($fstBpkbNo,$fstReqNo)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fstBpkbNo = ? AND fstReqNo = ?  AND fblOut !='1'";
        $qr = $this->db->query($ssql, [$fstBpkbNo,$fstReqNo]);
        //echo $this->db->last_query();
        //die();
        $rwBpkb = $qr->row();

        $data = [
            "requestbpkb" => $rwBpkb
        ];
        return $data;
    }
}