<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Contoh extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model("msbranches_model");
    }

    function index_get() {
        $branchCode = $this->input->get('branch_code');
        //$date = date();
		$token = $this->input->get('token');

		$branchData = $this->msbranches_model->getDataBranch($branchCode);
        $this->response($branchData, 200);
    }
}
?>