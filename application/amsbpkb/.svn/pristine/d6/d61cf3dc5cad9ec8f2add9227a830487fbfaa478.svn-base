<?php
   defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

class Coba extends REST_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public $menuName="coba";
    public function __construct() {
       parent::__construct();
       $this->load->model("msbranches_model");
    }
       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
	public function index_get()
	{
        
		$branchCode = $this->input->get('branch_code');
        //$date = date();
		$token = $this->input->get('token');

		$branchData = $this->msbranches_model->getDataBranch($branchCode);
        if ($branchData)
        {
            // Set the response and exit
            $this->response($branchData, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No branch were found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        if ( "OPTIONS" === $_SERVER['REQUEST_METHOD'] ) {
            die();
        }
         
        
	}
   
      
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    	
}