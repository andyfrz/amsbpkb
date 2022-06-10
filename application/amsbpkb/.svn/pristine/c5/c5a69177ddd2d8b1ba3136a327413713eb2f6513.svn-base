<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mscolours_model extends MY_Model
{
    public $tableName = "tbcolours";
    public $pkey = "fstColourCode";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fstColourCode)
    {
        $ssql = "select * from " . $this->tableName . " where fstColourCode = ?";
        $qr = $this->db->query($ssql, [$fstColourCode]);
        $rwColour = $qr->row();
        $data = [
            "colours" => $rwColour
        ];
        return $data;
    }

    
    public function getBranchById($fstColourCode){
        $ssql = "select * from " . $this->tableName . " where fstColourCode = ?";
        $qr = $this->db->query($ssql,[$fstColourCode]);
        $row = $qr->row();
        return $row;
    }

    public function getBranchReport($fstColourCode){
        $ssql = "select * from " . $this->tableName . " where fstColourCode = ?";
        $qr = $this->db->query($ssql,[$fstColourCode]);
        $row = $qr->row();
        $data = [
            "branch" => $row
        ];
        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fstColourCode',
            'label' => 'Colour Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];


        $rules[] = [
            'field' => 'fstColourName',
            'label' => 'Colour Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

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
        $ssql = "select fstColourCode,fstColourName from " . $this->tableName . " ";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

}
