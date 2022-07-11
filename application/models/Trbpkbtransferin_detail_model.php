<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trbpkbtransferin_detail_model extends MY_Model {
    public $tableName = "trbpkbtransferindetails";
    public $pkey = "finRecId";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        return $rules;
    }

    public function deleteByHeaderId($fstTransferInNo)
    {
        $ssql = "DELETE FROM " . $this->tableName . " WHERE fstTransferInNo = ?";
		$this->db->query($ssql,[$fstTransferInNo]);
    }
}