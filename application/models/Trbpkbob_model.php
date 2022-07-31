<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Trbpkbob_model extends MY_Model
{
    public $tableName = "trbpkb";
    public $pkey = "finId";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($finId)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE finId = ?";
        $qr = $this->db->query($ssql, [$finId]);
        $rwBpkb = $qr->row();

        $data = [
            "bpkb" => $rwBpkb
        ];
        return $data;
    }


    public function getRules($mode = "ADD", $finId = 0)
    {
        $fstBpkbNo = $this->input->post("fstBpkbNo");
        $fstEngineNo = $this->input->post("fstEngineNo");
        $fstChasisNo = $this->input->post("fstChasisNo");
        $rules = [];

        if ($fstBpkbNo != "" && $mode =="ADD"){
            $rules[] = [
                'field' => 'fstBpkbNo',
                'label' => 'BPKB No',
                'rules' => 'is_unique[trbpkb.fstBpkbNo]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
                )
            ];
        }else{
            $rules[] = [
                'field' => 'fstBpkbNo',
                'label' => 'BPKB No',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s tidak boleh kosong'
                )
            ];
        }

        $rules[] = [
            'field' => 'fdtBpkbDate',
            'label' => 'BPKB Date',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fstCustomerName',
            'label' => 'Customer',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fstNik',
            'label' => 'NIK',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        if ($fstEngineNo != "" && $mode =="ADD"){
            $rules[] = [
                'field' => 'fstEngineNo',
                'label' => 'Engine No',
                'rules' => 'is_unique[trbpkb.fstEngineNo]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
                )
            ];
        }else{
            $rules[] = [
                'field' => 'fstEngineNo',
                'label' => 'Engine No',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s tidak boleh kosong'
                )
            ];
        }

        if ($fstChasisNo != "" && $mode =="ADD"){
            $rules[] = [
                'field' => 'fstChasisNo',
                'label' => 'Chasis No',
                'rules' => 'is_unique[trbpkb.fstChasisNo]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
                )
            ];
        }else{
            $rules[] = [
                'field' => 'fstChasisNo',
                'label' => 'Chasis No',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s tidak boleh kosong'
                )
            ];
        }


        return $rules;
    }

    // Untuk mematikan fungsi otomatis softdelete dari MY_MODEL
    /*public function delete($key, $softdelete = false){
		parent::delete($key,$softdelete);
    }*/

    public function getAllList()
    {
        $ssql = "SELECT * FROM " . $this->tableName . " ";
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
