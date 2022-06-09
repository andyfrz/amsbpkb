<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Trprincipalinvoice_model extends MY_MODEL {

    public $tableName = "trprincipalinvoices";
    public $pkey = "finId";

    public function __construct() {
        parent::__construct();
    }

    public function getCheckin($finSalesTrxId)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE finSalesTrxId = ? AND fstPrincipalInvoiceStatus ='CHECKIN'";
        $qr = $this->db->query($ssql, [$finSalesTrxId]);
        $rwinvoicecheckin = $qr->row();

        $data = [
            "invoicecheckin" => $rwinvoicecheckin
        ];
        return $data;
    }

    
    public function add_new($table,$data){
		return $this->db->insert($table,$data);
	}

    public function deleteById($finId){
        $ssql = "delete from " . $this->tableName . " where finId = ?";
        $this->db->query($ssql,[$finId]);
    }
}