<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trbpkbopname_detail_model extends MY_Model {
    public $tableName = "trbpkbopnamedetails";
    public $pkey = "finRecId";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        return $rules;
    }

    public function deleteByHeaderId($fstOpnameNo)
    {
        $user = $this->aauth->user();
        $activeUser = $user->fstUserCode;
        $ssql = "DELETE FROM " . $this->tableName . " WHERE fstOpnameNo = ? AND fin_insert_id = ?";
		$this->db->query($ssql,[$fstOpnameNo,$activeUser]);
    }

    public function cekbpkb($fstBpkbNo,$fstOpnameNo)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fstBpkbNo = ? AND fstOpnameNo = ? ";
        $qr = $this->db->query($ssql, [$fstBpkbNo,$fstOpnameNo]);
        //echo $this->db->last_query();
        //die();
        $rwBpkb = $qr->row();

        $data = [
            "outbpkb" => $rwBpkb
        ];
        return $data;
    }
}