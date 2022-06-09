<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msbrandtypes_model extends MY_Model
{
    public $tableName = "tbbrandtypes";
    public $pkey = "finBrandTypeId";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($finBrandTypeId)
    {
        $ssql = "select * from " . $this->tableName . " where finBrandTypeId = ?";
        $qr = $this->db->query($ssql, [$finBrandTypeId]);
        $rwBrandtype = $qr->row();
        $data = [
            "brandtypes" => $rwBrandtype
        ];
        return $data;
    }


    public function getRules($mode = "ADD", $finBrandTypeId = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fstBrandCode',
            'label' => 'Branch Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];


        $rules[] = [
            'field' => 'fstBrandName',
            'label' => 'Branch Name',
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
        $ssql = "select finBrandTypeID,fstBrandCode,fstBrandName from " . $this->tableName . " ";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

}
