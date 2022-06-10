<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Msbpkbtrxs_detail_model extends MY_Model {
    public $tableName = "tbbpkbtrxpicdetails";
    public $pkey = "finTrxId";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        return $rules;
    }

    public function deleteByHeaderId($finTrxId)
    {
        $ssql = "DELETE FROM " . $this->tableName . " WHERE finTrxId = $finTrxId";
        $this->db->query($ssql);
    }
}