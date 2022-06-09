<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msbpkbtrxs_model extends MY_Model
{
    public $tableName = "tbbpkbtrxs";
    public $pkey = "finTrxId";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($finTrxId)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE finTrxId = ?";
        $qr = $this->db->query($ssql, [$finTrxId]);
        $rwBpkbtrx = $qr->row();

        $ssql = "SELECT a.finTrxId,a.fstUserCode,b.fstUserName FROM tbbpkbtrxpicdetails a LEFT JOIN tbusers b ON a.fstUserCode = b.fstUserCode WHERE a.finTrxId = ?";
        $qr = $this->db->query($ssql, [$finTrxId]);
        $rsTrxdetail = $qr->result();

        $data = [
            "bpkbtrx" => $rwBpkbtrx,
            "trxdetail" => $rsTrxdetail
        ];
        return $data;
    }


    public function getRules($mode = "ADD", $finTrxId = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fstTrxDescription',
            'label' => 'Trx Description',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];


        $rules[] = [
            'field' => 'fstTrxType',
            'label' => 'Trx Type',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];


        return $rules;
    }

    // Untuk mematikan fungsi otomatis softdelete dari MY_MODEL
    /*public function delete($key, $softdelete = false){
		parent::delete($key,$softdelete);
    }*/

    public function getAllList()
    {
        $ssql = "SELECT finTrxId,fstTrxDescription FROM " . $this->tableName . " ";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }


    public function deleteDetail($finTrxId){
		$ssql = "delete from tbbpkbtrxpicdetails where finTrxId =?";
		$this->db->query($ssql,[$finTrxId]);
		throwIfDBError();
	}


}
