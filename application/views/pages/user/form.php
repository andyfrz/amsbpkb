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
	<h1><?=lang("User")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("User") ?></a></li>
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
			<form id="frmUser" class="form-horizontal" action="<?=site_url()?>master/user/add" method="POST" enctype="multipart/form-data">				
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class="form-group">
						<label for="fst_user_code" class="col-sm-2 control-label"><?=lang("User Code")?> *</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="fstUserCode" placeholder="<?=lang("User Code")?>" name="fstUserCode" value="<?= $fstUserCode ?>">
							<div id="fstUserCode_err" class="text-danger"></div>
						</div>
						<label for="fst_user_name" class="col-sm-2 control-label"><?=lang("User Name")?> *</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="fstUserName" placeholder="<?=lang("User Name")?>" name="fstUserName" value="<?= set_value("fstUserName") ?>">
							<div id="fstUserName_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_mobile_no" class="col-md-2 control-label"><?=lang("Mobile No")?> *</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fstMobileNo" placeholder="<?=lang("Mobile No")?>" name="fstMobileNo">
							<div id="fstMobileNo_err" class="text-danger"></div>
						</div>

						<label for="fst_email" class="col-md-2 control-label"><?=lang("Email")?> *</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fstEmail" placeholder="<?=lang("Email")?>" name="fstEmail">
							<div id="fstEmail_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group special-access" style="display:none">
						<label for="fbl_is_admin" class="col-sm-2 control-label"><?=lang("Admin")?></label>
						<div class="col-md-4">
							<label class="checkbox-inline"><input id="fblIsAdmin" name="fblIsAdmin" type="checkbox" value="1"><?=lang("Admin/Special Access")?></label>
						</div>

						<label for="fstDealerCode" class="col-md-2 control-label"><?= lang("Dealer") ?></label>
						<div class="col-md-4">
							<select class="form-control" id="fstDealerCode" name="fstDealerCode">
							<option value="">ALL</option>
								<?php
									$dealerList = $this->msdealers_model->getAllList();
									foreach($dealerList as $dealer){
										echo "<option value='$dealer->fstDealerCode'>$dealer->fstDealerName</option>";
									}
								?>
							</select>
							<div id="fstDealerCode_err" class="text-danger"></div>
						</div>
					</div>
				</div>
				<!-- end box body -->
				<!--<form class="form-horizontal edit-mode ">	
					<div class="form-group">
						<div class="col-md-12">
							<button id="btn-add-user-detail" class="btn btn-primary btn-sm pull-right edit-mode"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Detail") ?></button>
						</div>
						<div class="col-md-12">
							<table id="tbl_user_detail" class="table table-bordered table-hover" style="width:100%;"></table>
						</div>							
					</div>
				</form>-->

				<div class="box-footer text-right">
					
				</div>
				<!-- end box-footer -->	
			</form>
		</div>
	</div>
</section>

<script type="text/javascript" info="INIT">
    $(function(){
        $("#fstDealerCode").val(null).change();
	});
</script>

<script type="text/javascript">

	$(function() {

		<?php if($mode == "EDIT"){?>
			init_form($("#fstUserCode").val());
		<?php } ?>
		<?php if($specialAccess){?>
			$(".special-access").show();
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			//data = new FormData($("#frmUser")[0]);
			data = $("#frmUser").serializeArray();

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>master/user/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>master/user/ajx_edit_save";
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
										window.location.href = "<?= site_url() ?>master/user/add";
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
						$("#fstUserCode").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT

						$('#fst_user_name').prop('readonly', true);
						$("#tabs-user-detail").show();
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

		/*$("#fst_avatar").change(function(event){
			event.preventDefault();
			var reader = new FileReader();
			reader.onload = function (e) {
			   $("#imgAvatar").attr('src', e.target.result);
			}
			reader.readAsDataURL(this.files[0]);
		});*/

		/*$(".datepicker").datepicker({
			format:"yyyy-mm-dd"
		});*/
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/user/add")
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
				url:"<?= site_url() ?>master/user/delete/" + $("#fstUserCode").val(),
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
									window.location.href = "<?= site_url() ?>master/user/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fstUserCode").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fstUserCode').prop('readonly', true);
				}
			});
		});


		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/user/lizt");
		});


		App.fixedSelect2();
	});


	function init_form(fstUserCode){
		//alert("Init Form");
		var fstUserCode = $("#fstUserCode").val();
		var url = "<?=site_url()?>master/user/fetch_data/" + fstUserCode;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.user);

				$.each(resp.user, function(name, val){
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

				$('#fstUserCode').prop('readonly', true);

				if (resp.user.fblIsAdmin == "1"){
					$("#fblIsAdmin").prop("checked",true);
				}else{
					$("#fblIsAdmin").prop("checked",false);
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