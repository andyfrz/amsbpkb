<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/dataTables.checkboxes.css">

<style type="text/css">
	.border-0{
		border: 0px;
	}
	td{
		padding: 2px; !important 		
	}
    .nav-tabs-custom>.nav-tabs>li.active>a{
        font-weight:bold;
        border-left-color: #3c8dbc;
        border-right-color: #3c8dbc;
        border-style:fixed;
    }
    .nav-tabs-custom>.nav-tabs{
        border-bottom-color: #3c8dbc;        
        border-bottom-style:fixed;
    }
	.form-group{
		margin-bottom: 5px;
	}
	.checkbox label, .radio label {
		font-weight:700;
	}
</style>

<section class="content-header">
	<h1><?=lang("Warehouse")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Warehouse") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title title"><?=$title?></h3>
				<div class="btn-group btn-group-sm  pull-right">					
					<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
					<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
					<!--<a id="btnPrinted" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>-->
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
			<!-- end box header -->
			
			<!-- form start -->
			<form id="frmWarehouse" class="form-horizontal" action="<?=site_url()?>master/bpkbwarehouse/add" method="POST" enctype="multipart/form-data">				
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class="form-group">
						<label for="finWarehouseId" class="col-sm-2 control-label"><?=lang("Warehouse Id")?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="finWarehouseId" placeholder="<?=lang("Warehouse Id")?>" name="finWarehouseId" value="<?= $finWarehouseId ?>" readonly>
							<div id="finWarehouseId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fstWarehouseName" class="col-sm-2 control-label"><?=lang("Warehouse Name")?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="fstWarehouseName" placeholder="<?=lang("Warehouse Name")?>" name="fstWarehouseName">
							<div id="fstWarehouseName_err" class="text-danger"></div>
						</div>
					</div>
                    <div class="form-group">
						<label for="fstPersonInCharge" class="col-sm-2 control-label"><?=lang("PIC")?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="fstPersonInCharge" placeholder="<?=lang("Person In Charge")?>" name="fstPersonInCharge">
							<div id="fstPersonInCharge_err" class="text-danger"></div>
						</div>
					</div>
					<div class="form-group">
						<label for="fstPhoneNo" class="col-md-2 control-label"><?=lang("Phone No")?> *</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fstPhoneNo" placeholder="<?=lang("Phone No")?>" name="fstPhoneNo">
							<div id="fstPhoneNo_err" class="text-danger"></div>
						</div>

						<label for="fst_email" class="col-md-2 control-label"><?=lang("Email")?> *</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fstEmail" placeholder="<?=lang("Email")?>" name="fstEmail">
							<div id="fstEmail_err" class="text-danger"></div>
						</div>
					</div>

                    <div class="form-group">
						<label for="fstAddress" class="col-sm-2 control-label"><?=lang("Address")?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="fstAddress" placeholder="<?=lang("Address")?>" name="fstAddress">
							<div id="fstAddress_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fblisMainWarehouse" class="col-sm-2 control-label"><?=lang("Main")?></label>
						<div class="col-md-2">
							<label class="checkbox-inline"><input id="fblisMainWarehouse" name="fblisMainWarehouse" type="checkbox" value="1"><?=lang("Is Main warehouse")?></label>
							<div id="fblisMainWarehouse_err" class="text-danger"></div>
						</div>
					</div>
				</div>
				<!-- end box body -->

				<div class="box-footer text-right">
					
				</div>
				<!-- end box-footer -->	
			</form>
		</div>
	</div>
</section>

<script type="text/javascript">

	$(function() {

		<?php if($mode == "EDIT"){?>
			init_form($("#finWarehouseId").val());
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			//data = new FormData($("#frmWarehouse")[0]);
			data = $("#frmWarehouse").serializeArray();

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>master/bpkbwarehouse/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>master/bpkbwarehouse/ajx_edit_save";
			}

			App.blockUIOnAjaxRequest("Please wait while saving data.....");
			$.ajax({
				type: "POST",
				//enctype: 'multipart/form-data',
				url: url,
				data: data,
				//processData: false,
				//contentType: false,
				//cache: false,
				timeout: 600000,
				success: function (resp) {	
					if (resp.message != "")	{
						$.alert({
							title: 'Message',
							content: resp.message,
							buttons : {
								OK : function(){
									if(resp.status == "SUCCESS"){
										window.location.href = "<?= site_url() ?>master/bpkbwarehouse/add";
										return;
									}
								},
							}
						});
					}

					if(resp.status == "VALIDATION_FORM_FAILED" ){
						//Show Error
						errors = resp.data;
						for (key in errors) {
							$("#"+key+"_err").html(errors[key]);
						}
					}else if(resp.status == "SUCCESS") {
						data = resp.data;
						$("#finWarehouseId").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT

						$('#fstWarehouseName').prop('readonly', true);
						console.log(data.data_image);
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}
			});
		});

		/*$(".datepicker").datepicker({
			format:"yyyy-mm-dd"
		});*/
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/bpkbwarehouse/add")
		});

		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
			$.ajax({
				url:"<?= site_url() ?>master/bpkbwarehouse/delete/" + $("#finWarehouseId").val(),
			}).done(function(resp){
				//consoleLog(resp);
				$.unblockUI();
				if (resp.message != "")	{
					$.alert({
						title: 'Message',
						content: resp.message,
						buttons : {
							OK : function() {
								if (resp.status == "SUCCESS") {
									window.location.href = "<?= site_url() ?>master/bpkbwarehouse/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#finWarehouseId").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fstWarehouseName').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/bpkbwarehouse/lizt");
		});

	});


	function init_form(finWarehouseId){
		//alert("Init Form");
		var finWarehouseId = $("#finWarehouseId").val();
		var url = "<?=site_url()?>master/bpkbwarehouse/fetch_data/" + finWarehouseId;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.bpkbwarehouse);

				$.each(resp.bpkbwarehouse, function(name, val){
					var $el = $('[name="'+name+'"]'),
					type = $el.attr('type');
					switch(type){
						case 'checkbox':
							$el.attr('checked', 'checked');
							break;
						case 'radio':
							$el.filter('[value="'+val+'"]').attr('checked', 'checked');
							break;
						default:
							$el.val(val);
							console.log(val);
					}
				});

				if (resp.bpkbwarehouse.fblisMainWarehouse == "1"){
					$("#fblisMainWarehouse").prop("checked",true);
				}else{
					$("#fblisMainWarehouse").prop("checked",false);
				}	

			},

			error: function (e) {
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}
		});
	}

</script>
<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?= base_url() ?>bower_components/datatables.net/dataTables.checkboxes.min.js"></script>