<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Trbpkbopname_model extends MY_Model
{
    public $tableName = "trbpkbopname";
    public $pkey = "fstOpnameNo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fstOpnameNo,$mode)
    {
        $user = $this->aauth->user();
        $activeUser = $user->fstUserCode;

        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fstOpnameNo = ?";
        $qr = $this->db->query($ssql, [$fstOpnameNo]);
        $rwHeader = $qr->row();

        if ($mode == "EDIT"){
            $ssql = "SELECT * FROM trbpkbopnamedetails WHERE fstOpnameNo = ? AND fin_insert_id = ?";
            $qr = $this->db->query($ssql, [$fstOpnameNo,$activeUser]);
        }else{
            $ssql = "SELECT * FROM trbpkbopnamedetails WHERE fstOpnameNo = ?";
            $qr = $this->db->query($ssql, [$fstOpnameNo]);
        }
        //echo $this->db->last_query();
        //die();
        $rsdetail = $qr->result();

        $data = [
            "opnameHeader" => $rwHeader,
            "opnameDetail" => $rsdetail
        ];
        return $data;
    }

    public function opnameExist($fdtOpnameStartDate,$warehouse)
    {

        $ssql = "SELECT * FROM " . $this->tableName . " WHERE DATE(fdtOpnameStartDate) = ? AND finWarehouseId = ?";
        $qr = $this->db->query($ssql, [$fdtOpnameStartDate,$warehouse]);
        //echo $this->db->last_query();
        //die();
        $rwTrx = $qr->row();

        $data = [
            "opname" => $rwTrx
        ];
        return $data;
    }

    public function getReady($fstOpnameNo)
    {
        $user = $this->aauth->user();
        $activeUser = $user->fstUserCode;
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fstOpnameNo = ? AND fin_insert_id = ?";
        $qr = $this->db->query($ssql, [$fstOpnameNo,$activeUser]);
        //echo $this->db->last_query();
        //ie();
        $rwOpname = $qr->row();

        $data = [
            "bpkbopname" => $rwOpname
        ];
        return $data;
    }

    public function closeOpname($fstOpnameNo){
        $user = $this->aauth->user();
        $activeUser = $user->fstUserCode;
        $closeDate = date ("Y-m-d H:i:s");
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fstOpnameNo = ? AND fin_insert_id = ?";
        $qr = $this->db->query($ssql, [$fstOpnameNo,$activeUser]);
        //echo $this->db->last_query();
        //die();
        $rwOpname = $qr->row();
        if($rwOpname==null){
            throw new CustomException(lang("Not Allowed to Close Opname No : $fstOpnameNo !"),3003,"FAILED",[]);
        }
        $ssql = "UPDATE " . $this->tableName . " SET fstOpnameStatus = 'WAITING_PROCESS',fdtOpnameEndDate ='$closeDate' WHERE fstOpnameNo = ?";
        $qr = $this->db->query($ssql,[$fstOpnameNo]);
        //echo $this->db->last_query();
        //die();


    }


    public function getRules($mode = "ADD")
    {
        $rules = [];
        
        $rules[] = [
            'field' => 'fdtOpnameStartDate',
            'label' => 'Opname Startdate',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'finWarehouseId',
            'label' => 'Warehouse',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];



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
        $prefix = "OP-";
		$query = $this->db->query("SELECT MAX(fstOpnameNo) as max_id FROM " . $this->tableName . " WHERE fstOpnameNo like '".$prefix.$tahun."%'");
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


    public function deleteDetail($fstOpnameNo){
		$ssql = "DELETE FROM trbpkbopnamedetails WHERE fstOpnameNo =?";
		$this->db->query($ssql,[$fstOpnameNo]);
		throwIfDBError();
	}

    public function postingLogBpkb($fstOpnameNo){
		$this->load->model("trlog_bpkb_model");

		$ssql ="SELECT * FROM " . $this->tableName . " WHERE fstOpnameNo = ?";
		$qr = $this->db->query($ssql,[$fstOpnameNo]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid TRANSFER IN NO"),404,"FAILED",[]);	
		}
		$ssql = "SELECT * FROM trbpkbopnamedetails where fstOpnameNo = ?";
		$qr = $this->db->query($ssql,[$fstOpnameNo]);
		$details = $qr->result();

		foreach($details as $dataD){
			//BPKB Log Detail
            $ssql ="SELECT * FROM trbpkb WHERE fstBpkbNo = ?";
            $qr = $this->db->query($ssql,[$dataD->fstBpkbNo]);
            $bpkb = $qr->row();
            if ($bpkb == null){
                throw new CustomException(lang("invalid BPKB No"),404,"FAILED",[]);	
            }
            $trxInfo = $dataD->fstOpnameNo."->".$dataD->fstNotes;
			$data = [
				"fstBpkbNo"=>$dataD->fstBpkbNo,
				"finTrxId"=>$bpkb->finTrxId,
                "fstTrxSource"=>"M_IN",
				"finWarehouseId"=>$dataH->finWarehouseId,
				"fdtTrxDate"=>$dataH->fdtTransferInDate,
				"fstTrxInfo"=>$trxInfo,
                "fdbIn"=>1,
                "fdbOut"=>0,
				"fst_active"=>"A"
			];
			$this->trlog_bpkb_model->insert($data);

            //Update BPKB Status
            $ssql = "UPDATE trbpkb SET fstBpkbStatus = 'CHECKIN' WHERE fstBpkbNo = ?";
			$this->db->query($ssql,[$dataD->fstBpkbNo]);

            //SET fblOut 
            $ssql = "UPDATE trbpkbtransferoutdetails SET fblIn = '1' WHERE fstBpkbNo = ? AND fstTransferOutNo = ? ";
            $this->db->query($ssql,[$dataD->fstBpkbNo,$dataH->fstTransferOutNo]);
		}

	}

    public function unpostingLogBpkb($fstOpnameNo){
		$this->load->model("trlog_bpkb_model");

		$ssql ="SELECT * FROM " . $this->tableName . " WHERE fstOpnameNo = ?";
		$qr = $this->db->query($ssql,[$fstOpnameNo]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid TRANSFER IN NO"),404,"FAILED",[]);	
		}

		$ssql = "SELECT * FROM trbpkbopnamedetails where fstOpnameNo = ?";
		$qr = $this->db->query($ssql,[$fstOpnameNo]);
		$details = $qr->result();

		foreach($details as $dataD){
			//Update BPKB Log Detail
            $ssql ="SELECT * FROM trbpkb WHERE fstBpkbNo = ?";
            $qr = $this->db->query($ssql,[$dataD->fstBpkbNo]);
            $bpkb = $qr->row();
            if ($bpkb == null){
                throw new CustomException(lang("invalid BPKB No"),404,"FAILED",[]);	
            }
            $ssql ="SELECT * FROM trbpkblogs WHERE fstTrxInfo like ? AND fstBpkbNo = ?";
            $qr = $this->db->query($ssql,[$fstOpnameNo.'%',$dataD->fstBpkbNo]);
            //echo $this->db->last_query();
            //die();
            $lastlog = $qr->row();
            if ($lastlog != null){
                $trxInfo = "***" .$lastlog->fstTrxInfo;
                $ssql = "UPDATE trbpkblogs SET fstTrxInfo = '$trxInfo',fst_active='S' WHERE fstBpkbNo = ? AND fstTrxSource ='M_IN'";
                $this->db->query($ssql,[$dataD->fstBpkbNo]);
                //echo $this->db->last_query();
                //die();
            }
            
            //Update BPKB Status
            if ($bpkb->finSalesTrxId !='' || $bpkb->finSalesTrxId != null){
                $ssql = "UPDATE trbpkb SET fstBpkbStatus = 'CHECKIN' WHERE fstBpkbNo = ?";
                $this->db->query($ssql,[$dataD->fstBpkbNo]);
            }else{
                $ssql = "UPDATE trbpkb SET fstBpkbStatus = 'OB_CHECKIN' WHERE fstBpkbNo = ?";
                $this->db->query($ssql,[$dataD->fstBpkbNo]);
            }

            //UNSET fblOut 
            $ssql = "UPDATE trbpkbtransferoutdetails SET fblIn = '0' WHERE fstBpkbNo = ? AND fstTransferOutNo = ? ";
            $this->db->query($ssql,[$dataD->fstBpkbNo,$dataH->fstTransferOutNo]);

		}

	}



}
