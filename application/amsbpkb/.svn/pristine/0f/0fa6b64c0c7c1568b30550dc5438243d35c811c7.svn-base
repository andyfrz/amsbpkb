<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends MY_Model
{
	public $tableName = "tbusers";
	public $pkey = "fstUserCode";

	public function  __construct()
	{
		parent::__construct();
	}

	public function getDataById($fstUserCode)
	{
		//$ssql = "select * from " . $this->tableName ." where fin_user_id = ?";
		$ssql = "select *
			from " . $this->tableName . "
			where fstUserCode = ?";


		$qr = $this->db->query($ssql, [$fstUserCode]);
		//echo $this->db->last_query();
        //die();
		$rwUser = $qr->row();
		/*if ($rwUser) {
			if (file_exists(FCPATH . 'assets/app/users/avatar/avatar_' . $rwUser->fst_user_code . '.jpg')) {
				$avatarURL = site_url() . 'assets/app/users/avatar/avatar_' . $rwUser->fst_user_code . '.jpg';
			} else {

				$avatarURL = site_url() . 'assets/app/users/avatar/default.jpg';
			}
			$rwUser->avatarURL = $avatarURL;
		}*/

		//Branch
		/*$ssql = "SELECT a.*,b.fst_branch_name FROM tbusersdetail a LEFT JOIN tbbranch b on a.fst_branch_code = b.fst_branch_code where a.fst_user_code = ? ";
		$qr = $this->db->query($ssql,[$fst_user_code]);
		$rsDetail = $qr->result();*/

		$data = [
			"user" => $rwUser
		];

		return $data;
	}

	public function getRules($mode = "ADD", $code = ""){

		$rules = [];

		if($mode !="EDIT"){
			$rules[] = [
				'field' => 'fstUserCode',
				'label' => 'User code',
				'rules' => array(
					'required',
					'is_unique[tbusers.fstUserCode.fstUserName.' . $code . ']'
				),
				'errors' => array(
					'required' => '%s tidak boleh kosong',
					'is_unique' => '%s harus unik',
				),
			];
		}

		$rules[] = [
			'field' => 'fstUserName',
			'label' => 'User name',
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

	public function getRulesCp(){
		$activeUser = $this->aauth->user();
		$password = $activeUser->fstPassword;
		$CurrentPassword = $this->input->post("current_password");

		$rules = [];

		if (md5($CurrentPassword) != $password) {

			$rules[] =
				[
					'field' => 'current_password',
					'label' => 'Current Password',
					'rules' => 'matches[' . $password . ']',
					'errors' => array(
						'matches' => 'Wrong password'
					)
				];
		} else { }

		$rules[] = [
			'field' => 'new_password1',
			'label' => 'New Password',
			'rules' => 'required|min_length[3]|matches[new_password2]',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
				'min_length' => 'Panjang %s paling sedikit 3 character',
				'matches' => 'not matches with Repeat password'
			)
		];

		$rules[] = [
			'field' => 'new_password2',
			'label' => 'Repeat Password',
			'rules' => 'required|min_length[3]',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
				'min_length' => 'Panjang %s paling sedikit 3 character'
			)
		];


		return $rules;
	}

	public function getAllList()
	{
		$ssql = "select fst_user_code,fst_user_name from " . $this->tableName . " order by fst_user_name";
		$qr = $this->db->query($ssql, []);
		$rs = $qr->result();
		return $rs;
	}

	public function getUserList_R()
	{
		$ssql = "select fstUserCode,fstUserName from " . $this->tableName . " order by fstUserCode";
		$qr = $this->db->query($ssql, []);
		$rs = $qr->result();
		return $rs;
	}

	public function get_Users()
	{
		$query = $this->db->get('tbusers');
		return $query->result_array();
	}

	public function getPrintUser($branchName,$departmentName,$userId_awal,$userId_akhir){
        if ($branchName == 'null'){
            $branchName ="";
        }
        if ($departmentName == 'null'){
            $departmentName ="";
        }
        $ssql = "SELECT a.*,b.fst_department_name,c.fst_group_name,c.fin_level,d.fst_branch_name,d.fbl_is_hq FROM users a 
				LEFT JOIN departments b on a.fin_department_id = b.fin_department_id 
				LEFT JOIN  usersgroup c on a.fin_group_id = c.fin_group_id 
				LEFT JOIN  msbranches d on a.fin_branch_id = d.fin_branch_id 
                WHERE a.fin_branch_id like ?  AND a.fin_department_id like ?
                AND a.fin_user_id >= '$userId_awal' AND a.fin_user_id <= '$userId_akhir' ORDER BY a.fst_fullname ";
        $query = $this->db->query($ssql,['%'.$branchName.'%','%'.$departmentName.'%']);
        //echo $this->db->last_query();
        //die();
        $rs = $query->result();

        return $rs;
	}
	
}
