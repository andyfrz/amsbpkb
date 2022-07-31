<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<section class="content-header">
	<h1><?=lang("Approval")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Tools") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>


<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
				    <h3 class="box-title title"><?=$title?></h3>
			    </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><label>Need Approval</label></a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><label>Histories</label></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div style="margin-bottom:5px;margin-top:20px">
                                <div style="float:left">
                                    <label class="control-label">Date Range :</label>
                                    <input id="daterange_needapproval" type="TEXT" class="daterangepicker form-control" style="position:static;display:inline" value='' />
                                </div>
                                <!--<div style="float:left">
                                    <label class="control-label" style="margin-left:10px">Module :</label>
                                    <select id="module_needapproval" style="margin-left:10px;width:148px;padding:6px">
                                        <option value="ALL">All Module</option>
                                        <option value="PO">PO</option>
                                        <option value="SO">SO</option>
                                        <option value="PP">PP</option>
                                        <option value="RJ">RJ</option>
                                    </select>
                                </div>-->
                                <div style="float:right">
                                    <label class="control-label">Search by :</label>
                                    <select id="selectSearch" class="form-control"  style="display:inline;width:148px">
                                        <option value='fstReqNo'>Request No</option>
                                        <option value='fstDealerName'>Dealer</option>
                                    </select>
                                </div>
                                <div style="clear:both"></div>
                            </div>
                        
                            
                            <table id="tblNeedApproval" style="width:100%"></table>                            
                            
                        </div> <!-- /.tab-pane -->            
                        <div class="tab-pane" id="tab_2">
                            <div style="margin-bottom:5px;margin-top:20px">
                                <div style="float:left">
                                    <label class="control-label">Date Range :</label>
                                    <input id="daterange_historyapproval" type="TEXT" class="daterangepicker form-control" style="position:static;display:inline" value='' />
                                </div>
                                <!--<div style="float:left">
                                    <label class="control-label" style="margin-left:10px">Module :</label>
                                    <select id="module_historyapproval" style="margin-left:10px;width:148px;padding:6px">
                                        <option value="ALL">All Module</option>
                                        <option value="PO">PO</option>
                                        <option value="SO">SO</option>
                                        <option value="PP">PP</option>
                                        <option value="RJ">RJ</option>
                                    </select>
                                </div>-->
                                <div style="clear:both"></div>
                                <div style="float:right">
                                    <label class="control-label">Search by :</label>
                                    <select id="selectSearchHist" class="form-control"  style="display:inline;width:148px">
                                        <option value='fstReqNo'>Request No</option>
                                        <option value='fstDealerName'>Dealer</option>
                                    </select>
                                </div>
                                <div style="clear:both"></div>
                            </div>
                            <table id="tblHistApproval" style="width:100%"></table>
                        </div><!-- /.tab-pane -->
                                            
                    </div> <!-- /.tab-content -->                    
                </div>
            </div>
            <!-- end box header -->
        </div>
    </div>
</section>


<script type="text/javascript">
    var selectedRecord;

    $(function(){
        reloadNeedApproval();
        $('.nav-tabs a').on('shown.bs.tab', function(event){            
            var x = $(event.target).text();         // active tab
            var y = $(event.relatedTarget).text();  // previous tab
            if (x  == "Need Approval"){
                reloadNeedApproval();
            }

            if (x  == "Histories"){
                reloadHistories();
            }
            
        });
        /*$("#btn-do-approve").click(function(e){
            e.preventDefault();
            doApproval(1);
        })*/
        $("#btn-do-reject").click(function(e){
            e.preventDefault();
            doApproval(0);
        })

        $("#daterange_needapproval").on('daterangepicker.change',function(e){
            e.preventDefault();
            console.log("daterangepicker change");
        })
        /*$("#module_needapproval").change(function(e){
            e.preventDefault();
            reloadNeedApproval();
		});*/
        /*$("#module_historyapproval").change(function(e){
            e.preventDefault();
            reloadHistories();
		});*/
        
    });

    function reloadNeedApproval(){
        if ( $.fn.DataTable.isDataTable( '#tblNeedApproval' ) ) {
            $('#tblNeedApproval').DataTable().clear().destroy();
        }

        $("#tblNeedApproval").DataTable({
            ajax: {
                url:"<?=site_url()?>trx/approval/fetch_need_approval_list",
            },
			columns:[
				{"title" : "Request No","width": "5%",sortable:true,data:"fstReqNo",visible:true},
				{"title" : "Request Date","width": "5%",sortable:false,data:"fdtReqDate",visible:true},				
                {"title" : "Dealer","width": "12%",sortable:false,data:"fstDealerName",visible:true},
                {"title" : "Type","width": "10%",sortable:false,data:"finTransferType",visible:true},
                {"title" : "Approved By","width": "10%",sortable:false,data:"fstTrxPICApprovedBy",visible:true},
                {"title" : "Approved Date","width": "8%",sortable:false,data:"fdtTrxPICApprovedDatetime",visible:true},
                {"title" : "Action","width": "5%",sortable:false,className:'dt-body-center text-center',
                    render: function(data,type,row){
                        action = "<a class='btn-approve need-confirm' href='#' title='<?=lang("Approve???")?>'><i style='font-size:14pt;margin-right:10px' class='fa fa-check-circle-o'></i></a>";
                        action += "<a class='btn-view' href='#' title='<?=lang("Transaksi")?>'><i style='font-size:14pt;color:lime' class='fa fa-bars'></i></a>";                        
                        return action;                        
                    }
                },
            ],
            dataSrc:"data",
			processing: true,
			serverSide: true,
        }).on('preXhr.dt', function ( e, settings, data ) {
            data.dateRange = $('#daterange_needapproval').val();
            //data.optionModule = $('#module_needapproval').val();
            data.optionSearch = $('#selectSearch').val();
		}).on('draw',function(){
            $('.need-confirm').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				title: "<?=lang('Hapus data ini ?')?>",
				rootSelector: '.need-confirm',
				// other options
			});	
        });

        $("#tblNeedApproval").on("click",".btn-approve",function(e){    
            e.preventDefault();
            doApproval($(this));
        });
      
        $("#tblNeedApproval").on("click",".btn-view",function(e){    
            showTransaction($(this),false);
        });


    }
    
    function reloadHistories(){
        
        if ( $.fn.DataTable.isDataTable( '#tblHistApproval' ) ) {
            $('#tblHistApproval').DataTable().clear().destroy();
        }

        $("#tblHistApproval").DataTable({
            ajax: {
                url:"<?=site_url()?>trx/approval/fetch_hist_approval_list",
            },
			columns:[
				{"title" : "Request No","width": "5%",sortable:true,data:"fstReqNo",visible:true},
				{"title" : "Request Date","width": "5%",sortable:false,data:"fdtReqDate",visible:true},				
                {"title" : "Dealer","width": "12%",sortable:false,data:"fstDealerName",visible:true},
                {"title" : "Type","width": "10%",sortable:false,data:"finTransferType",visible:true},
                {"title" : "Approved By","width": "10%",sortable:false,data:"fstTrxPICApprovedBy",visible:true},
                {"title" : "Approved Date","width": "8%",sortable:false,data:"fdtTrxPICApprovedDatetime",visible:true},
                {"title" : "Action","width": "5%",sortable:false,className:'dt-body-center text-center',
                    render: function(data,type,row){
                        action = "<a class='btn-cancel need-confirm' href='#' title='<?=lang("Batal Approve???")?>'><i style='font-size:14pt;margin-right:10px;color:red' class='fa fa-ban'></i></a>";    
                        action += "<a class='btn-view' href='#' title='<?=lang("Transaksi")?>'><i style='font-size:14pt;color:lime' class='fa fa-bars'></i></a>";                        
                        return action;                        
                    }
                },
            ],
            dataSrc:"data",
			processing: true,
			serverSide: true,
        }).on('preXhr.dt', function ( e, settings, data ) {
            data.dateRange = $('#daterange_historyapproval').val();
            //data.optionModule = $('#module_historyapproval').val();
            data.optionSearch = $('#selectSearchHist').val();
		}).on('draw',function(){
            $('.need-confirm').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				title: "<?=lang('Hapus data ini ?')?>",
				rootSelector: '.need-confirm',
				// other options
			});	
        });
      
        $("#tblHistApproval").on("click",".btn-view",function(e){    
            showTransaction($(this),true);
        });

        $("#tblHistApproval").on("click",".btn-cancel",function(e){    
            cancelApproval($(this));
        });
    }

    function cancelApproval(element){
        t = $('#tblHistApproval').DataTable();
        var trRow = element.parents('tr');
        data = t.row(trRow).data(); 
        
        dataPost = {
            <?=$this->security->get_csrf_token_name()?> : "<?=$this->security->get_csrf_hash()?>",
            fstReqNo: data.fstReqNo,
        };

        App.blockUIOnAjaxRequest("<?=lang("Please wait .....")?>");
        $.ajax({
            url:"<?= site_url() ?>trx/approval/cancelApproval/",
            data:dataPost,
            method:"POST"
        }).done(function(resp){
            if (resp.message != "")	{
                $.alert({
                    title: 'Message',
                    content: resp.message,
                    buttons : {
                        OK : function(){
                            if(resp.status == "SUCCESS"){
                                //window.location.href = "<?= site_url() ?>tr/sales_order/lizt";
                                return;
                            }
                        },
                    }
                });
            }
            if(resp.status == "SUCCESS") {
                //remove row
                t.row(trRow).remove().draw();                
            }
        });

    }

    function doApproval(element){
        t = $('#tblNeedApproval').DataTable();
        var trRow = element.parents('tr');
        data = t.row(trRow).data(); 
        dataPost = {
            <?=$this->security->get_csrf_token_name()?> : "<?=$this->security->get_csrf_hash()?>",
            fstReqNo: data.fstReqNo,
        };

        App.blockUIOnAjaxRequest("<?=lang("Please wait .....")?>");
        $.ajax({
            url:"<?= site_url() ?>trx/approval/doApproval/",
            data:dataPost,
            method:"POST"

        }).done(function(resp){
            if (resp.message != "")	{
                $.alert({
                    title: 'Message',
                    content: resp.message,
                    buttons : {
                        OK : function(){
                            if(resp.status == "SUCCESS"){
                                //window.location.href = "<?= site_url() ?>tr/sales_order/lizt";
                                return;
                            }
                        },
                    }
                });
            }
            if(resp.status == "SUCCESS") {
                //remove row
                //selectedRecord.remove();
                t.row(trRow).remove().draw();   
                //trRow.remove();
            }
        });
    }

    function showTransaction(element,isHist){
        //alert("Show");
        if(isHist){
            t = $('#tblHistApproval').DataTable();
        }else{
            t = $('#tblNeedApproval').DataTable();
        }
        
        var trRow = element.parents('tr');
        data = t.row(trRow).data(); 

        url = "<?= site_url() ?>trx/bpkbrequest/view/" + data.fstReqNo;
        window.open(url);
    }



</script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
