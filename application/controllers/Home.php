<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('dashboard_model');
	}
	
	public function index(){				
		$this->load->library("menus");
		
		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$this->list["title"] = "Dashboard";
		//$this->list["ttlDownload"] = formatNumber($this->dashboard_model->getTtlDownload());
		//$this->list["ttlSuccess"] = formatNumber($this->dashboard_model->getTtlSuccess());
		//$this->list["ttlOnProgress"] = formatNumber($this->dashboard_model->getTtlOnProgress());
		//$this->list["ttlFailed"] = formatNumber($this->dashboard_model->getTtlFailed());
		$this->list["approval"] = $this->dashboard_model->get_ttl_approval();
		$this->list["ttlNeedApproval"] = formatNumber($this->dashboard_model->get_ttl_approval());
		//$this->list["refreshToken"] = $this->session->userdata("refresh_token");

		$page_content = $this->parser->parse('pages/dashboard/dashboard', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main',$this->data);
	}

	public function history($fst_branch_code){				
		$this->load->library("menus");
		
		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$this->list["title"] = "History download data";
		$this->list["branch"] = $fst_branch_code;
		$this->list["downloads"] = $this->dashboard_model->getHistoryDownload($fst_branch_code);

		$page_content = $this->parser->parse('pages/tr/history', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main',$this->data);
	}
	
}