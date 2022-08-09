<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msbrands_model extends MY_Model
{
    public $tableName = "tbbrands";
    public $pkey = "fstBrandCode";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fstBrandCode)
    {
        $ssql = "select * from " . $this->tableName . " where fstBrandCode = ?";
        $qr = $this->db->query($ssql, [$fstBrandCode]);
        $rwBrand = $qr->row();
        $data = [
            "brands" => $rwBrand
        ];
        return $data;
    }

    public function isExist($fstBrandCode){
        $ssql = "select * from " . $this->tableName . " where fstBrandCode = ?";
        $qr = $this->db->query($ssql, [$fstBrandCode]);
        $rwBrand = $qr->row();

		if ($rwBrand == null){            
			return false;
		}else{
			return true;
		}

	}

    
    public function getBranchById($fstBrandCode){
        $ssql = "select * from " . $this->tableName . " where fstBrandCode = ?";
        $qr = $this->db->query($ssql,[$fstBrandCode]);
        $row = $qr->row();
        return $row;
    }

    public function getBranchReport($fstBrandCode){
        $ssql = "select * from " . $this->tableName . " where fstBrandCode = ?";
        $qr = $this->db->query($ssql,[$fstBrandCode]);
        $row = $qr->row();
        $data = [
            "branch" => $row
        ];
        return $data;
    }

    public function getRules($mode = "ADD", $id = "")
    {
        $fstBrandCode = $this->input->post("fstBrandCode");
        $fstGenesysBrandCode = $this->input->post("fstGenesysBrandCode");
        $rules = [];

        if ($fstBrandCode != "" && $mode =="ADD"){
            $rules[] = [
                'field' => 'fstBrandCode',
                'label' => 'Branch Code',
                'rules' => 'is_unique[tbbrands.fstBrandCode]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
                )
            ];
        }else{
            $rules[] = [
                'field' => 'fstBrandCode',
                'label' => 'Branch Code',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s tidak boleh kosong'
                )
            ];
        }

        $rules[] = [
            'field' => 'fstBrandName',
            'label' => 'Branch Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        if ($fstGenesysBrandCode != "" && $mode =="ADD"){
            $rules[] = [
                'field' => 'fstGenesysBrandCode',
                'label' => 'Genesys Code',
                'rules' => 'is_unique[tbbrands.fstGenesysBrandCode]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
                )
            ];
        }else{
            $rules[] = [
                'field' => 'fstGenesysBrandCode',
                'label' => 'Genesys Code',
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
        $ssql = "select fstBrandCode,fstBrandName from " . $this->tableName . " ";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

}
