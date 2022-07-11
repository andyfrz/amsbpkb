<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trbpkbtransferout_detail_model extends MY_Model {
    public $tableName = "trbpkbtransferoutdetails";
    public $pkey = "finRecId";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        return $rules;
    }

    public function deleteByHeaderId($fstTransferOutNo)
    {
        $ssql = "DELETE FROM " . $this->tableName . " WHERE fstTransferOutNo = ?";
		$this->db->query($ssql,[$fstTransferOutNo]);
    }

    public function cekbpkb($fstBpkbNo,$fstTransferOutNo)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fstBpkbNo = ? AND fstTransferOutNo = ? ";
        $qr = $this->db->query($ssql, [$fstBpkbNo,$fstTransferOutNo]);
        //echo $this->db->last_query();
        //die();
        $rwBpkb = $qr->row();

        $data = [
            "outbpkb" => $rwBpkb
        ];
        return $data;
    }
}