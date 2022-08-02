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

    public function getCheckin($finId)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE finId = ? AND fstBpkbStatus ='CHECKIN'";
        $qr = $this->db->query($ssql, [$finId]);
        //echo $this->db->last_query();
        //ie();
        $rwBpkbCheckin = $qr->row();

        $data = [
            "bpkbcheckin" => $rwBpkbCheckin
        ];
        return $data;
    }

    public function getRules($mode = "ADD", $finTrxId = 0)
    {
        $rules = [];

        if ($mode == "ADD"){
            $rules[] = [
                'field' => 'finSalesTrxId',
                'label' => 'Trx Id',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s tidak boleh kosong'
                )
            ];
        }


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

        if($mode == "ADD"){
            $rules[] = [
                'field' => 'fstBpkbNo',
                'label' => 'BPKB No',
                'rules' => 'is_unique[trbpkb.fstBpkbNo]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
                )
            ];
    
        }


        return $rules;
    }

    
    public function add_new($table,$data){
		return $this->db->insert($table,$data);
	}

    public function deleteById($finId){
        $ssql = "delete from " . $this->tableName . " where finId = ?";
        $this->db->query($ssql,[$finId]);
    }

    public function updateLogBpkb($finId){

        $fstBpkbNo = $this->input->post("fstBpkbNo");
        $fstTrxInfo = $this->input->post("fstInfo");
        $fdtTrxDate = date("Y-m-d H:i:s");

		$ssql ="SELECT * FROM " . $this->tableName . " WHERE finId = ?";
		$qr = $this->db->query($ssql,[$finId]);
        //echo $this->db->last_query();
        //die();
		$dataBpkb = $qr->row();
		if ($dataBpkb == null){
			throw new CustomException(lang("invalid ID BPKB"),404,"FAILED",[]);	
		}

        $ssql = "UPDATE trbpkblogs SET fstBpkbNo ='$fstBpkbNo',fstTrxInfo='$fstTrxInfo',fdtTrxDate='$fdtTrxDate' WHERE finTrxId = ? AND fstTrxSource ='CHECKIN'";
        $this->db->query($ssql,[$dataBpkb->finSalesTrxId]);

	}
}