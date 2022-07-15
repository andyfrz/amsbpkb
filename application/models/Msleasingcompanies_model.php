<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msleasingcompanies_model extends MY_Model
{
    public $tableName = "tbleasingcompanies";
    public $pkey = "fstLeasingCode";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fstLeasingCode)
    {
        $ssql = "select * from " . $this->tableName . " where fstLeasingCode = ?";
        $qr = $this->db->query($ssql, [$fstLeasingCode]);
        $rwLeasing = $qr->row();

        $data = [
            "leasingcompanies" => $rwLeasing
        ];
        return $data;
    }


    public function getRules($mode = "ADD", $id = "")
    {
        $fstLeasingCode = $this->input->post("fstLeasingCode");
        $fstGenesysLeasingCode = $this->input->post("fstGenesysLeasingCode");
        $rules = [];

        if ($fstLeasingCode != "" && $mode =="ADD"){
            $rules[] = [
                'field' => 'fstLeasingCode',
                'label' => 'Leasing Code',
                'rules' => 'is_unique[tbleasingcompanies.fstLeasingCode]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
                )
            ];
        }else{
            $rules[] = [
                'field' => 'fstLeasingCode',
                'label' => 'Leasing Code',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s tidak boleh kosong'
                )
            ];
        }


        $rules[] = [
            'field' => 'fstLeasingName',
            'label' => 'Leasing Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        
        if($mode == "ADD" && $fstGenesysLeasingCode !=""){
            $rules[] = [
                'field' => 'fstGenesysLeasingCode',
                'label' => 'Genesys Code',
                'rules' => 'is_unique[tbleasingcompanies.fstGenesysLeasingCode]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
                )
            ];
    
        }else{
            $rules[] = [
                'field' => 'fstGenesysLeasingCode',
                'label' => 'Genesys Code',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s tidak boleh kosong'
                )
            ];
        }

        /*$rules[] = [
            'field' => 'fbl_is_hq',
            'label' => 'HQ',
            'rules' => 'is_unique[msbranches.fin_branch_id.fbl_is_hq.' . $id . ']',
            'errors' => array(
                'is_unique' => '%s is more one'
            )
        ];*/


        return $rules;
    }

    // Untuk mematikan fungsi otomatis softdelete dari MY_MODEL
    /*public function delete($key, $softdelete = false){
		parent::delete($key,$softdelete);
    }*/

    public function getAllList()
    {
        $ssql = "select fstLeasingCode,fstLeasingName from " . $this->tableName . " ";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

}
