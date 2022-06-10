<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class User_detail_model extends MY_Model
{
    public $tableName = "tbusersdetail";
    public $pkey = "fst_user_code";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fst_user_code)
    {
        $ssql = "select * from " . $this->tableName . " where fst_user_code = ?";
        $qr = $this->db->query($ssql, [$fst_user_code]);
        $rwUsersbranch = $qr->row();
        $data = [
            "usersbranch" => $rwUsersbranch
        ];
        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_branch_code',
            'label' => 'Group Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }

    // Untuk mematikan fungsi otomatis softdelete dari MY_MODEL
    /*public function delete($key, $softdelete = false){
		parent::delete($key,$softdelete);
    }*/
    public function deleteByHeaderId($fst_user_code)
    {
        $ssql = "delete from " . $this->tableName . " where fst_user_code = ?";
        $this->db->query($ssql,[$fst_user_code]);
        //echo $this->db->last_query();
        //die();
    }
}
