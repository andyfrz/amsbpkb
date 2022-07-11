<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script src="<?=base_url()?>bower_components/jquery/jquery.md5.js"></script>
<section class="content-header">
    <h1><?= lang("Sales TRX") ?><small><?= lang("") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Transaction") ?></a></li>
        <li><a href="#"><?= lang("Sales TRX") ?></a></li>
    </ol>
</section>

<section class="content">
<div class="row">
	<div class="col-md-12">
    <div class="box box-default">
		<div class="box-header with-border">
		  <i class="fa fa-cloud-download"></i>
		  <h3 class="box-title">Sales Data</h3>
		</div>
		<!-- /.box-header -->
        <form id="frmDownload" class="form-horizontal" action="<?= site_url() ?>trx/salestrx/download_api" method="POST" enctype="multipart/form-data">
            <div class="box-body">
            <input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
                <div class="form-group">
                    <div class="col-sm-12">
                    <label class="radio"><input type="radio" id="import_excel" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Import sales data (Excel)")?></label>
                    <label class="radio"><input type="radio" id="import_api" class="rpt_layout" name="rpt_layout" value="2" onclick="handleRadioClick(this);"><?=lang("Import sales data (API)")?></label>								
                        <!--<div class="radio">
                        <label>
                            <input type="radio" name="opsi_download" id="import_excel" value="1" checked="">
                            Import sales data (Excel)
                        </label>
                        </div>
                        <div class="radio">
                        <label>
                            <input type="radio" name="opsi_download" id="import_api" value="2">
                            Import sales data (API)
                        </label>
                        </div>-->
                    </div>
                </div>
                <div id="salesdate" class="form-group row" style="display:none">
                    <label for="fdtSalesDate" class="col-sm-2 control-label"><?=lang("Sales date :")?></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control datepicker" id="fdtSalesDate" name="fdtSalesDate"/>
                        </div>
                        <div id="fdtSalesDate_err" class="text-danger"></div>
                        <!-- /.input group -->
                    </div>
                    <label for="fdtSalesDate2" class="col-sm-2 control-label"><?=lang("Sales date :")?></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control datepicker" id="fdtSalesDate2" name="fdtSalesDate2"/>
                        </div>
                        <div id="fdtSalesDate2_err" class="text-danger"></div>
                        <!-- /.input group -->
                    </div>
                </div>
                <button type="button"  id="btnImport" href="#" title="<?=lang("Import Excel")?>" class="btn btn-primary btn-block"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
                <button type="button"  id="btnLOG" href="#" title="<?=lang("Download")?>" class="btn btn-primary btn-block" hidden ><i class="fa fa-cloud-download" aria-hidden="true"></i></button>
            </div>
            <!-- /.box-body -->
        </form>
	  </div>
	  <!-- /.box -->
	</div>
	<!-- /.col -->
</div>
</section>

<div class="modal fade in" id="modal-import">
  <div class="modal-dialog">
	<div class="modal-content">
    <?php echo form_open_multipart('trx/salestrx/import',array('id' => 'frm-upload')); ?>
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
	  </div>
	  <div class="modal-body">
			
			<div class="form-group">
				<label for="exampleInputFile">Import</label>
				<input type="file" id="exampleInputFile" name="file" required>

				<p class="margin">Format File .xls | .xlsx | maks size. 10 Mb</p>
			</div>
			
	  </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			<button type="submit" class="btn btn-primary">Import</button>
		</div>	 
		<?php echo form_close(); ?>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript" info="init">
	$(function(){
		$("#fdtSalesDate").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");
        $("#fdtSalesDate2").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");
        //$("#salesdate").hide();		
	});

    function handleRadioClick(myRadio) {
        if (myRadio.value == "2"){
            //alert("2 ya")
            $("#salesdate").show();

        }else{
            $("#salesdate").hide();

        }
    }
</script>

<script type="text/javascript">
    $(function() {

        var refreshToken = "<?= $refreshToken ?>";
        var $token = $.md5('221106T1853');
        
        var data = {
            [SECURITY_NAME]:SECURITY_VALUE,
            "fdtSalesDate": $("#fdtSalesDate").val(),
            "fdtSalesDate2":$("#fdtSalesDate2").val(),
        };
        $("#btnLOG").click(function(event){
            event.preventDefault();
            insertData(data);
            /*$.ajax({
                type: 'GET',
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                url: 'http://36.94.119.139:4000/api/leasingAccount/getList',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "Authorization": "Bearer "+ refreshToken
                },
                data: {
                    'branchCode':'SYM',
                    'filter':'B',
                    'filterBy':'NameOnSTNK',
                    'sortBy':'NameOnSTNK',
                    'sortOrder':'asc',
                    'pageNumber':'0',
                    'pageSize':'6'
                },
                success: function(resp) {
                    detail = new Array();
                    $.each(resp.Items, function(i, v) {
                        detail.push(v);
                    });
                    /*data.push({
                        name:"dataDetail",
                        value: JSON.stringify(detail)
                    });*/
                    //console.log(detail);
                    //window.location.replace("<?=site_url()?>trx/salestrx/" + detail);
             /*              

                },
                error: function (e) {
                    $("#Temp").text(e.error_description);
                },
            }).always(function(){
			
		    });*/
        });

        function insertData(dealers){
            var dataSubmit = $("#frmDownload").serializeArray();
            var detail = new Array();
            datas = dealers;
            $.each(datas,function(i,v){
                detail.push(v);
            });

            dataSubmit.push({
                name:"detail",
                value: JSON.stringify(detail)
            });
            url =  "<?= site_url() ?>trx/salestrx/download_salestrx/";

		    App.blockUIOnAjaxRequest("<?=lang("Please wait while saving data.....")?>");
            $.ajax({
                type: "POST",
                //enctype: 'multipart/form-data',
                url: url,
                data: dataSubmit,
                timeout: 600000,
                success: function (resp) {				
                    if (resp.message != "")	{
                        $.alert({
                            title: 'Message',
                            content: resp.message,
                            buttons : {
                                OK : function(){
                                    if(resp.status == "SUCCESS"){
                                        //$("#btnNew").trigger("click");
                                        //return;
                                    }
                                },
                            }
                        });
                    }
                },
                error: function (e) {
                    $("#result").text(e.responseText);
                    $("#btnSubmit").prop("disabled", false);
                },
            }).always(function(){
                
            });
        }

        function getList(data,callbackFunc){
            App.getValueAjax({
                type: 'GET',
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                url: 'http://36.94.119.139:4000/api/leasingAccount/getList',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "Authorization": "Bearer "+ refreshToken
                },
                data: {
                    'branchCode':'SYM',
                    'filter':'B',
                    'filterBy':'NameOnSTNK',
                    'sortBy':'NameOnSTNK',
                    'sortOrder':'asc',
                    'pageNumber':'0',
                    'pageSize':'6'
                },
                callback:function(value){
                    detail = new Array();
                    $.each(value,function(i,item){
                        detail.push(v);
                    });

                    if( typeof callbackFunc === "function" ){
                        callbackFunc(value);
                    }
                    
                }
            });
        }

        $("#btnImport").click(function(event) {
            event.preventDefault();
            //$("#modal-import").modal('show');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                url: 'http://36.94.119.139:4000/user/authenticate',

                data: JSON.stringify({
                    'UserCode':'backup',
                    'UserPassword':'bfdf97ae01c73d83a58ec41f78a4291f',
                    'BranchCode':'SYM',
                    'DealerCode':'SYSYM'
                }),
                success: function(resp) {
                    console.log(resp);
                    token = resp.RefreshToken;
                    //window.location.replace("<?=site_url()?>master/user/update_token/" + token);
                }
            });
        });


    });
</script>
<script src="<?=base_url()?>bower_components/jquery/jquery.md5.js"></script>