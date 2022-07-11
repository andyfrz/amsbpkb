<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Trbpkbcheckin_model extends MY_MODEL {

    public $tableName = "trbpkb";
    public $pkey = "finId";

    public function __construct() {
        parent::__construct();
    }

    public function getDataById($finId)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE finId = ?";
        $qr = $this->db->query($ssql, [$finId]);
        //echo $this->db->last_query();
        //die();
        $rwBpkb = $qr->row();

        $data = [
            "bpkb" => $rwBpkb
        ];
        return $data;
    }

    public function getTrxId($finSalesTrxId)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE finSalesTrxId = ?";
        $qr = $this->db->query($ssql, [$finSalesTrxId]);
        //echo $this->db->last_query();
        //ie();
        $rwtrxBpkb = $qr->row();

        $data = [
            "trxbpkb" => $rwtrxBpkb
        ];
        return $data;
    }

    public function getRules($mode = "ADD", $finTrxId = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'finSalesTrxId',
            'label' => 'Trx Id',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];


        $rules[] = [
            'field' => 'fstBpkbNo',
            'label' => 'BPKB No',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdtBpkbDate',
            'label' => 'BPKB Date',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        //if($mode == "ADD"){
            $rules[] = [
                'field' => 'fstBpkbNo',
                'label' => 'BPKB No',
                'rules' => 'is_unique[trbpkb.fstBpkbNo]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
                )
            ];
    
        //}


        return $rules;
    }

    
    public function add_new($table,$data){
		return $this->db->insert($table,$data);
	}

    public function deleteById($finId){
        $ssql = "delete from " . $this->tableName . " where finId = ?";
        $this->db->query($ssql,[$finId]);
    }
}