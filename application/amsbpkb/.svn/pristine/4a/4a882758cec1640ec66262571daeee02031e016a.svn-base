<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Msbpkbwarehouse_model extends MY_Model
{
	public $tableName = "tbbpkbwarehouse";
	public $pkey = "finWarehouseId";

	public function  __construct()
	{
		parent::__construct();
	}

	public function getDataById($finWarehouseId)
	{
		//$ssql = "select * from " . $this->tableName ." where fin_user_id = ?";
		$ssql = "select *
			from " . $this->tableName . "
			where finWarehouseId = ?";
		$qr = $this->db->query($ssql, [$finWarehouseId]);
		//echo $this->db->last_query();
        //die();
		$rwWarehouse = $qr->row();
		$data = [
			"bpkbwarehouse" => $rwWarehouse
		];

		return $data;
	}

	public function getRules($mode = "ADD", $id = 0){

		$rules = [];

		$rules[] = [
			'field' => 'fstWarehouseName',
			'label' => 'Warehouse name',
			'rules' => array(
				'required',
			),
			'errors' => array(
				'required' => '%s tidak boleh kosong'
			),
		];
		/*$rules[] = [
			'field' => 'fbl_admin',
			'label' => 'Admin',
			'rules' => 'required',
			'errors' =>array(
				'required' => '%s tidak boleh kosong'
			)
		];*/

		return $rules;
	}

	public function getAllList()
	{
		$ssql = "select finWarehouseId,fstWarehouseName from " . $this->tableName . " order by fstWarehouseName";
		$qr = $this->db->query($ssql, []);
		$rs = $qr->result();
		return $rs;
	}

	
}
