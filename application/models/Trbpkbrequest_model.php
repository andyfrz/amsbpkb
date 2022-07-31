<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Trbpkbrequest_model extends MY_Model
{
    public $tableName = "trbpkbrequest";
    public $pkey = "fstReqNo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fstReqNo)
    {
        $user = $this->aauth->user();
        $activeDealer = $user->fstDealerCode;
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fstReqNo = ?";
        $qr = $this->db->query($ssql, [$fstReqNo]);
        //echo $this->db->last_query();
        //die();
        $rwReq = $qr->row();

        $ssql = "SELECT finRecId,fstReqNo,fstBpkbNo,fstNotes FROM trbpkbrequestdetails WHERE fstReqNo = ?";
        $qr = $this->db->query($ssql, [$fstReqNo]);
        $rsReqdetail = $qr->result();

        if ($activeDealer !=""){
            if ($activeDealer == $rwReq->fstDealerCode){
                $data = [
                    "request" => $rwReq,
                    "requestDetail" => $rsReqdetail
                ];
            }else{
                $data = [
                    "request" => "",
                    "requestDetail" => ""
                ];
            }

        }else{
            $data = [
                "request" => $rwReq,
                "requestDetail" => $rsReqdetail
            ];
        }
        return $data;
    }


    public function getRules($mode = "ADD")
    {
        $finTrxId = $this->input->post("hfinTrxId");
        $rules = [];
        
        $rules[] = [
            'field' => 'fdtReqDate',
            'label' => 'Request Date',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'hfinTrxId',
            'label' => 'BPKB Trx',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        if ($finTrxId == '1' || $finTrxId == '2' || $finTrxId == '3'){
            $rules[] = [
                'field' => 'fstDealerCode',
                'label' => 'Dealer',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s tidak boleh kosong'
                )
            ];
        }



        /*if($mode == "ADD"){
            $rules[] = [
                'field' => 'fstBpkbNo',
                'label' => 'BPKB No',
                'rules' => 'is_unique[trbpkb.fstBpkbNo]',
                'errors' => array(
                    'is_unique' => 'This %s already exists'
                )
            ];
    
        }*/


        return $rules;
    }

    public function GenerateNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("ym", strtotime ($trDate));
        $prefix = "REQ-";
		$query = $this->db->query("SELECT MAX(fstReqNo) as max_id FROM trbpkbrequest where fstReqNo like '".$prefix.$tahun."%'");
        //echo $this->db->last_query();
        //die();
		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'-'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}

    // Untuk mematikan fungsi otomatis softdelete dari MY_MODEL
    /*public function delete($key, $softdelete = false){
		parent::delete($key,$softdelete);
    }*/

    public function getAllList()
    {
        $ssql = "SELECT * FROM " . $this->tableName . " ";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

    public function getOutstandingReq()
    {
        $ssql = "SELECT fstReqNo,fdtReqDate FROM " . $this->tableName . " ";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }


    public function deleteDetail($fstReqNo){
		$ssql = "delete from trbpkbrequestdetails where fstReqNo =?";
		$this->db->query($ssql,[$fstReqNo]);
		throwIfDBError();
	}


    public function cekDealer($fstBpkbNo,$fstDealerCode)
    {
        $ssql = "SELECT * FROM trbpkb WHERE fstBpkbNo = ? AND fstDealerCode = ?";
        $qr = $this->db->query($ssql, [$fstBpkbNo,$fstDealerCode]);
        //echo $this->db->last_query();
        //die();
        $rwBpkb = $qr->row();

        $data = [
            "dealerbpkb" => $rwBpkb
        ];
        return $data;
    }

    public function approve($fstReqNo){
        $user = $this->aauth->user();
        $activeUser = $user->fstUserCode;
        $approvedDate = date ("Y-m-d H:i:s");
        $ssql = "select * from " . $this->tableName . " where fstReqNo = ?";
        $qr = $this->db->query($ssql,[$fstReqNo]);        
        $rw = $qr->row();
        if($rw==null){
            throw new CustomException(lang("No Request $fstReqNo tidak ditemukan !"),3003,"FAILED",[]);
        }
        $ssql = "UPDATE " . $this->tableName . " SET fstTrxPICApprovedBy = '$activeUser',fdtTrxPICApprovedDatetime ='$approvedDate' WHERE fstReqNo = ?";
        $qr = $this->db->query($ssql,[$fstReqNo]);
        //echo $this->db->last_query();
        //die();


    }

    public function cancelApprove($fstReqNo){
        
        $ssql = "select * from " . $this->tableName . " where fstReqNo = ?";
        $qr = $this->db->query($ssql,[$fstReqNo]);        
        $rw = $qr->row();
        if($rw==null){
            throw new CustomException(lang("No Request tidak ditemukan !"),3003,"FAILED",[]);
        }

        $ssql = "select * from trbpkbtransferout where fstReqNo = ?";
        $qr = $this->db->query($ssql,[$fstReqNo]);        
        $rw = $qr->row();
        if($rw!=null){
            throw new CustomException(lang("No Request sudah ada Transfer OUT !"),3003,"FAILED",[]);
        }

        $ssql = "UPDATE " . $this->tableName . " SET fstTrxPICApprovedBy = NULL,fdtTrxPICApprovedDatetime = NULL WHERE fstReqNo = ?";
        $qr = $this->db->query($ssql,[$fstReqNo]);


    }


}
