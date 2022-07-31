<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
    //Get Approval List
    public function get_ttl_approval(){
        $user = $this->aauth->user();
        $userActive = $user->fstUserCode;

        $ssql = "SELECT count(*) as ttl_approval FROM (SELECT a.*,b.fstUserCode FROM trbpkbrequest a 
        LEFT JOIN tbbpkbtrxpicdetails b ON a.finTrxId = b.finTrxId
        WHERE  b.fstUserCode = '$userActive' AND a.fstTrxPICApprovedBy IS NULL AND a.fdtTrxPICApprovedDatetime IS NULL) a";
        $qr = $this->db->query($ssql,[]);
        //echo $this->db->last_query();
        //die();
        $rw = $qr->row();
        return $rw->ttl_approval;

    }



}