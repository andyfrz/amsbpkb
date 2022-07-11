<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Trbpkbtransferout_model extends MY_Model
{
    public $tableName = "trbpkbtransferout";
    public $pkey = "fstTransferOutNo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fstTransferOutNo)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fstTransferOutNo = ?";
        $qr = $this->db->query($ssql, [$fstTransferOutNo]);
        //echo $this->db->last_query();
        //die();
        $rwOut = $qr->row();

        $ssql = "SELECT finRecId,fstTransferOutNo,fstBpkbNo,fstNotes FROM trbpkbtransferoutdetails WHERE fstTransferOutNo = ?";
        $qr = $this->db->query($ssql, [$fstTransferOutNo]);
        $rsOutdetail = $qr->result();

        $data = [
            "transferOut" => $rwOut,
            "transferOutDetail" => $rsOutdetail
        ];
        return $data;
    }


    public function getRules($mode = "ADD")
    {
        $TransferType = $this->input->post("finTransferType");
        $fstReqNo = $this->input->post("fstReqNo");
        $finToWarehouseId = $this->input->post("finToWarehouseId");
        $rules = [];
        
        $rules[] = [
            'field' => 'fdtTransferOutDate',
            'label' => 'Transfer Out Date',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'finTransferType',
            'label' => 'Transfer Type',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'finFromWarehouseId',
            'label' => 'Warehouse',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];



        if($TransferType == "2" && $fstReqNo ==""){
            $rules[] = [
                'field' => 'fstReqNo',
                'label' => 'Request No',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s tidak boleh kosong'
                )
            ];
    
        }

        if($finToWarehouseId != "" || $finToWarehouseId != null){
            $rules[] = [
                'field' => 'fdbDaysToWarehouse',
                'label' => 'Days To Warehouse',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s tidak boleh kosong'
                )
            ];
    
        }


        return $rules;
    }

    public function GenerateNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("ym", strtotime ($trDate));
        $prefix = "TO-";
		$query = $this->db->query("SELECT MAX(fstTransferOutNo) as max_id FROM " . $this->tableName . " WHERE fstTransferOutNo like '".$prefix.$tahun."%'");
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


    public function deleteDetail($fstTransferOutNo){
		$ssql = "DELETE FROM trbpkbtransferoutdetails WHERE fstTransferOutNo =?";
		$this->db->query($ssql,[$fstTransferOutNo]);
		throwIfDBError();
	}

    public function postingLogBpkb($fstTransferOutNo){
		$this->load->model("trlog_bpkb_model");

		$ssql ="SELECT * FROM " . $this->tableName . " WHERE fstTransferOutNo = ?";
		$qr = $this->db->query($ssql,[$fstTransferOutNo]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid TRANSFER OUT NO"),404,"FAILED",[]);	
		}
        if ($dataH->fstReqNo != "" || $dataH->fstReqNo != null){
            $ssql ="SELECT * FROM trbpkbrequest WHERE fstReqNo = ?";
            $qr = $this->db->query($ssql,[$dataH->fstReqNo]);
            $request = $qr->row();
        }
        $type = $dataH->finTransferType;
        switch ($type){
            case '1':
                $type = 'Mutasi Gudang';
                break;
            case '2':
                $type = 'Request Checkout';
                break;
            case '3':
                $type = 'Request Dealer';
                break;
            case '4':
                $type = 'Peminjaman';
                break;  
            case '5':
                $type = 'Perbaikan';
                break;
            case '6':
                $type = 'Proses Ulang';
                break;
            default:
                $type = 'Mutasi Gudang'; 
        }   

		$ssql = "SELECT * FROM trbpkbtransferoutdetails where fstTransferOutNo = ?";
		$qr = $this->db->query($ssql,[$fstTransferOutNo]);
		$details = $qr->result();

		foreach($details as $dataD){
			//BPKB Log Detail
            $ssql ="SELECT * FROM trbpkb WHERE fstBpkbNo = ?";
            $qr = $this->db->query($ssql,[$dataD->fstBpkbNo]);
            $bpkb = $qr->row();
            if ($bpkb == null){
                throw new CustomException(lang("invalid BPKB No"),404,"FAILED",[]);	
            }
            // merubah format TrxInfo dibawah ini harus rubah juga query like saat update LOGnya (unpostingLogBpkb)
            $trxInfo = $dataD->fstTransferOutNo."*".$type."*".$dataD->fstNotes;
			$data = [
				"fstBpkbNo"=>$dataD->fstBpkbNo,
				"finTrxId"=>$bpkb->finTrxId,
				"finWarehouseId"=>$dataH->finFromWarehouseId,
				"fdtTrxDate"=>$dataH->fdtTransferOutDate,
				"fstTrxInfo"=>$trxInfo,
                "fdbIn"=>0,
                "fdbOut"=>1,
				"fst_active"=>"A"
			];
			$finTransferType = $dataH->finTransferType;
			if ($finTransferType == "1" ){
				$data["fstTrxSource"]= "M_OUT";
			}else if ($finTransferType == "2" && ($request->finTrxId ="2" || $request->finTrxId ="3")){
				$data["fstTrxSource"] = "REQ";
			}else if ($finTransferType == "2" && $request->finTrxId ="4"){
				$data["fstTrxSource"] = "REQ_LEASING";
            }else if ($finTransferType == "2" && $request->finTrxId ="5"){
				$data["fstTrxSource"] = "REQ_TARIKAN";
            }
			$this->trlog_bpkb_model->insert($data);

            //Update BPKB Status
            $ssql = "UPDATE trbpkb SET fstBpkbStatus = 'CHECKOUT' WHERE fstBpkbNo = ?";
			$this->db->query($ssql,[$dataD->fstBpkbNo]);

            //UNSET fblOut 
            $ssql = "UPDATE trbpkbrequestdetails SET fblOut = '1' WHERE fstBpkbNo = ? AND fstReqNo = ? ";
            $this->db->query($ssql,[$dataD->fstBpkbNo,$dataH->fstReqNo]);
		}

	}

    public function unpostingLogBpkb($fstTransferOutNo){
		$this->load->model("trlog_bpkb_model");

		$ssql ="SELECT * FROM " . $this->tableName . " WHERE fstTransferOutNo = ?";
		$qr = $this->db->query($ssql,[$fstTransferOutNo]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid TRANSFER OUT NO"),404,"FAILED",[]);	
		}
        if ($dataH->fstReqNo != "" || $dataH->fstReqNo != null){
            $ssql ="SELECT * FROM trbpkbrequest WHERE fstReqNo = ?";
            $qr = $this->db->query($ssql,[$dataH->fstReqNo]);
            $request = $qr->row();
        }
		$ssql = "SELECT * FROM trbpkbtransferoutdetails WHERE fstTransferOutNo = ?";
		$qr = $this->db->query($ssql,[$fstTransferOutNo]);
		$details = $qr->result();

		foreach($details as $dataD){
			//Update BPKB Log Detail
            $ssql ="SELECT * FROM trbpkblogs WHERE fstTrxInfo like ? AND fstBpkbNo = ?";
            $qr = $this->db->query($ssql,[$fstTransferOutNo.'%',$dataD->fstBpkbNo]);
            //echo $this->db->last_query();
            //die();
            $lastlog = $qr->row();
            if ($lastlog != null){
                $trxInfo = "***" .$lastlog->fstTrxInfo;
                $ssql = "UPDATE trbpkblogs SET fstTrxInfo = '$trxInfo',fst_active='S' WHERE fstBpkbNo = ? AND (fstTrxSource ='M_OUT' OR fstTrxSource ='REQ' OR fstTrxSource ='REQ_LEASING' OR fstTrxSource ='REQ_TARIKAN')";
                $this->db->query($ssql,[$dataD->fstBpkbNo]);
                //echo $this->db->last_query();
                //die();
            }
            
            //Update BPKB Status
            $ssql = "UPDATE trbpkb SET fstBpkbStatus = 'CHECKIN' WHERE fstBpkbNo = ? AND (finSalesTrxId !='' OR finSalesTrxId is not null)";
            $this->db->query($ssql,[$dataD->fstBpkbNo]);

            $ssql = "UPDATE trbpkb SET fstBpkbStatus = 'OB_CHECKIN' WHERE fstBpkbNo = ? AND (finSalesTrxId ='' OR finSalesTrxId is null)";
            $this->db->query($ssql,[$dataD->fstBpkbNo]);

            //UNSET fblOut 
            $ssql = "UPDATE trbpkbrequestdetails SET fblOut = '0' WHERE fstBpkbNo = ? AND fstReqNo = ? ";
            $this->db->query($ssql,[$dataD->fstBpkbNo,$dataH->fstReqNo]);


		}

	}

    public function setRequest($fstBpkbNo,$fstReqNo)
    {
        $ssql = "UPDATE trbpkbrequestdetails SET fblOut = '1' WHERE fstBpkbNo = ? AND fstReqNo = ? ";
        $this->db->query($ssql,[$fstBpkbNo,$fstReqNo]);
    }

    public function unsetRequest($fstBpkbNo,$fstReqNo)
    {
        $ssql = "UPDATE trbpkbrequestdetails SET fblOut = '0' WHERE fstBpkbNo = ? AND fstReqNo = ? ";
        $this->db->query($ssql,[$fstBpkbNo,$fstReqNo]);
    }

    public function getPendingOutList(){
        $ssql ="SELECT a.fstTransferOutNo,a.finToWarehouseId,b.fblIn FROM trbpkbtransferout a LEFT JOIN trbpkbtransferoutdetails b ON a.fstTransferOutNo = b.fstTransferOutNo WHERE b.fblIn = '0' AND (a.finToWarehouseId !='' OR a.finToWarehouseId != null) GROUP BY a.fstTransferOutNo";
        $qr = $this->db->query($ssql, []);
		$rs = $qr->result();
		return $rs;
    }

    public function get_RequestList($finTransferType){
        $term = $this->input->get("term") ;
        $term = $term == null ? "" :$term;
        $ssql ="SELECT a.fstReqNo,a.fdtReqDate FROM trbpkbrequest a LEFT JOIN trbpkbrequestdetails b ON a.fstReqNo = b.fstReqNo  WHERE a.fstTrxPICApprovedBy !='' AND b.fblOut = '0' AND a.finTransferType = ? GROUP BY a.fstReqNo ";
        $qr = $this->db->query($ssql,[$finTransferType]);
        //echo $this->db->last_query();
        //die();                
        $rs= $qr->result();
		return $rs;
    }

    public function getRequestList(){
        $ssql ="SELECT a.fstReqNo,a.fdtReqDate FROM trbpkbrequest a LEFT JOIN trbpkbrequestdetails b ON a.fstReqNo = b.fstReqNo  WHERE a.fstTrxPICApprovedBy !='' AND b.fblOut = '0' GROUP BY a.fstReqNo ";
        $qr = $this->db->query($ssql, []);
        //echo $this->db->last_query();
        //die();                
        return $qr->result();
    }


    public function getBpkbNo($fstBpkbNo)
    {
        $ssql = "SELECT * FROM trbpkb WHERE fstBpkbNo = ?";
        $qr = $this->db->query($ssql, [$fstBpkbNo]);
        //echo $this->db->last_query();
        //die();
        $rwBpkb = $qr->row();

        $data = [
            "bpkb" => $rwBpkb
        ];
        return $data;
    }

    public function cekWarehouse($fstBpkbNo,$finWarehouseId)
    {
        $ssql = "SELECT * FROM trbpkb WHERE fstBpkbNo = ? AND finWarehouseId = ?";
        $qr = $this->db->query($ssql, [$fstBpkbNo,$finWarehouseId]);
        //echo $this->db->last_query();
        //die();
        $rwBpkb = $qr->row();

        $data = [
            "warehousebpkb" => $rwBpkb
        ];
        return $data;
    }




}
