<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
	public $data = [];
	public function index()
	{
		$this->load->model("users_model");
		$this->load->model("trlog_user_model");
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		if ($username != "") {
			$ssql = "SELECT * FROM tbusers  WHERE fstUserCode = ?";

			$query = $this->db->query($ssql, [$username]);
			//echo $this->db->last_query();
            //die();
			$rw = $query->row();
			$strIvalidLogin = "Invalid Username / Password";

			if ($rw) {
				if (md5($password) == $rw->fstPassword) {
					$this->session->set_userdata("active_user", $this->users_model->getDataById($rw->fstUserCode)["user"]);
					//$this->session->set_userdata("active_branch_id", $rw->fst_branch_code);
					$this->session->set_userdata("last_login_session", time());
					//$this->session->set_userdata("refresh_token", $rw->fst_branch_code);

					$this->trlog_user_model->log_user($type = "login",$username);

					if ($this->session->userdata("last_uri")) {
						redirect(site_url() . $this->session->userdata("last_uri"), 'refresh');
					} else {
						redirect(site_url() . 'home', 'refresh');
					}

				} else {
					$this->data["message"] = $strIvalidLogin;
				}
			} else {
				$this->data["message"] = $strIvalidLogin;
			}
		}
		$this->parser->parse('pages/login', $this->data);
		
	}

	public function signout($type = "logout")
	{
		$user = $this->aauth->user();
        $user_log = $user->fstUserCode;
		$this->load->model("trlog_user_model");
		$this->trlog_user_model->log_user($type = "logout",$user_log);

		$this->session->unset_userdata("active_user");
		if ($type != "expired") {
			$this->session->unset_userdata("last_uri");
		}
		redirect('/login', 'refresh');
	}
}
