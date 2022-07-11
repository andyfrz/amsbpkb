<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Trbpkbtransferin_model extends MY_Model
{
    public $tableName = "trbpkbtransferin";
    public $pkey = "fstTransferInNo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fstTransferInNo)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fstTransferInNo = ?";
        $qr = $this->db->query($ssql, [$fstTransferInNo]);
        //echo $this->db->last_query();
        //die();
        $rwIn = $qr->row();

        $ssql = "SELECT finRecId,fstTransferInNo,fstBpkbNo,fstNotes FROM trbpkbtransferindetails WHERE fstTransferInNo = ?";
        $qr = $this->db->query($ssql, [$fstTransferInNo]);
        $rsIndetail = $qr->result();

        $data = [
            "transferIn" => $rwIn,
            "transferInDetail" => $rsIndetail
        ];
        return $data;
    }


    public function getRules($mode = "ADD")
    {
        $rules = [];
        
        $rules[] = [
            'field' => 'fdtTransferInDate',
            'label' => 'Transfer In Date',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fstTransferOutNo',
            'label' => 'Transfer Out No',
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
        $prefix = "TI-";
		$query = $this->db->query("SELECT MAX(fstTransferInNo) as max_id FROM " . $this->tableName . " WHERE fstTransferInNo like '".$prefix.$tahun."%'");
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


    public function deleteDetail($fstTransferInNo){
		$ssql = "DELETE FROM trbpkbtransferindetails WHERE fstTransferInNo =?";
		$this->db->query($ssql,[$fstTransferInNo]);
		throwIfDBError();
	}

    public function postingLogBpkb($fstTransferInNo){
		$this->load->model("trlog_bpkb_model");

		$ssql ="SELECT * FROM " . $this->tableName . " WHERE fstTransferInNo = ?";
		$qr = $this->db->query($ssql,[$fstTransferInNo]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid TRANSFER IN NO"),404,"FAILED",[]);	
		}
		$ssql = "SELECT * FROM trbpkbtransferindetails where fstTransferInNo = ?";
		$qr = $this->db->query($ssql,[$fstTransferInNo]);
		$details = $qr->result();

		foreach($details as $dataD){
			//BPKB Log Detail
            $ssql ="SELECT * FROM trbpkb WHERE fstBpkbNo = ?";
            $qr = $this->db->query($ssql,[$dataD->fstBpkbNo]);
            $bpkb = $qr->row();
            if ($bpkb == null){
                throw new CustomException(lang("invalid BPKB No"),404,"FAILED",[]);	
            }
            $trxInfo = $dataD->fstTransferInNo."->".$dataD->fstNotes;
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

    public function unpostingLogBpkb($fstTransferInNo){
		$this->load->model("trlog_bpkb_model");

		$ssql ="SELECT * FROM " . $this->tableName . " WHERE fstTransferInNo = ?";
		$qr = $this->db->query($ssql,[$fstTransferInNo]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid TRANSFER IN NO"),404,"FAILED",[]);	
		}

		$ssql = "SELECT * FROM trbpkbtransferindetails where fstTransferInNo = ?";
		$qr = $this->db->query($ssql,[$fstTransferInNo]);
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
            $qr = $this->db->query($ssql,[$fstTransferInNo.'%',$dataD->fstBpkbNo]);
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
