<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Trsalestrx_model extends MY_MODEL {

    public $tableName = "trsalestrx";
    public $pkey = "finSalesTrxId";

    public function __construct() {
        parent::__construct();
    }
    
    public function getDataById($finSalesTrxId)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE finSalesTrxId = ?";
        $qr = $this->db->query($ssql, [$finSalesTrxId]);
        $rwSalestrx = $qr->row();

        $data = [
            "salestrx" => $rwSalestrx
        ];
        return $data;
    }

    public function add_new($table,$data){
		return $this->db->insert($table,$data);
	}

    public function deleteById($finSalesTrxId){
        $ssql = "delete from " . $this->tableName . " where finSalesTrxId = ?";
        $this->db->query($ssql,[$finSalesTrxId]);
    }

    public function is_present_sales($regist){
		$query = $this->db->get_where('trsalestrx',array('fstSPKNo' => $regist));
		//echo $this->db->last_query();
        //die();
		if($query->num_rows()>0){
			//ada dosen dengan NIP tersebut
			return TRUE;
		}else{
			return false;
		}
	}
}