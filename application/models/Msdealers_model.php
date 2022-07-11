<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msdealers_model extends MY_Model
{
    public $tableName = "tbdealers";
    public $pkey = "fstDealerCode";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fstDealerCode)
    {
        $ssql = "select * from " . $this->tableName . " where fstDealerCode = ?";
        $qr = $this->db->query($ssql, [$fstDealerCode]);
        $rwDealer = $qr->row();

        $data = [
            "dealers" => $rwDealer
        ];
        return $data;
    }


    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fstDealerCode',
            'label' => 'Dealer Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];


        $rules[] = [
            'field' => 'fstDealerName',
            'label' => 'Dealer Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

                
        if($mode == "ADD"){
            $rules[] = [
                'field' => 'fstGenesysDealerCode',
                'label' => 'Genesys Code',
                'rules' => 'is_unique[tbdealers.fstGenesysDealerCode]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
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
        $ssql = "select fstDealerCode,fstDealerName from " . $this->tableName . " order by fstDealerName";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

    public function add_new($table,$data){
		return $this->db->insert($table,$data);
	}

}
