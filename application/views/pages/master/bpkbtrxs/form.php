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
	<h1><?=lang("BPKB Trx")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("BPKP Trx") ?></a></li>
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
			<form id="frmBPKBTrx" class="form-horizontal" action="<?=site_url()?>master/bpkbtrxs/add" method="POST" enctype="multipart/form-data">				
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class="form-group">
						<label for="finTrxId" class="col-sm-2 control-label"><?=lang("Trx ID")?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="finTrxId" placeholder="<?=lang("Trx ID")?>" name="finTrxId" value="<?= $finTrxId ?>" readonly>
							<div id="finTrxId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fstTrxDescription" class="col-sm-2 control-label"><?=lang("Trx Description")?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="fstTrxDescription" placeholder="<?=lang("Trx Description")?>" name="fstTrxDescription">
							<div id="fstTrxDescription_err" class="text-danger"></div>
						</div>
					</div>
					<div class="form-group">
						<label for="fstTrxType" class="col-md-2 control-label"><?=lang("Trx Type")?> *</label>
						<div class="col-md-4">
                            <select class="form-control" id="fstTrxType" name="fstTrxType">
								<option value='+'><?=lang("+")?></option>
								<option value='-'><?=lang("-")?></option>
							</select>
						</div>

						<div class="col-md-4">
							<label class="checkbox-inline"><input id="fblIsSystemTrx" name="fblIsSystemTrx" type="checkbox" value="1"><?=lang("System Trx")?></label>
						</div>
					</div>
				</div>
				<!-- end box body -->
				<form class="form-horizontal edit-mode ">	
					<div class="form-group">
						<div class="col-md-12">
							<button id="btn-add-trx-detail" class="btn btn-primary btn-sm pull-right edit-mode"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("PIC Detail") ?></button>
						</div>
						<div class="col-md-12">
							<table id="tbl_trx_detail" class="table table-bordered table-hover" style="width:100%;"></table>
						</div>							
					</div>
				</form>

				<div class="box-footer text-right">
					
				</div>
				<!-- end box-footer -->	
			</form>
		</div>
	</div>
</section>

<div id="mdlTrxDetail" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:40%;min-width:400px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add PIC Detail") ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
                                <form class="form-horizontal ">
                                    <div class="form-group">
                                        <label for="select-pic" class="col-md-3 control-label"><?=lang("PIC")?></label>
                                        <div class="col-md-9">
                                            <select id="fstUserCode" class="form-control" name="fstUserCode"></select>
                                            <div id="fstUserCode_err" class="text-danger"></div>
                                        </div>
                                    </div>
                                </form>
                                <div class="modal-footer" style="width:100%;padding:10px" class="text-center">
                                    <button id="btn-add-detail" type="button" class="btn btn-primary btn-sm text-center" style="width:15%" ><?=lang("Add")?></button>
                                    <button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var action = '<a class="btn-edit" href="#" data-toggle="" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp; <a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
        $(function() {
            $("#btn-add-trx-detail").click(function(event) {
                event.preventDefault();
                $("#mdlTrxDetail").modal('show');
                $("#fstUserCode").empty();
            });
            $("#tbl_trx_detail").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [/*{
                        "title": "<?= lang("ID ") ?>",
                        "width": "5%",
                        data: "fin_id",
                        visible: false
                    },*/
                    {
                        "title": "<?= lang("User Code") ?>",
                        "width": "5%",
                        data: "fstUserCode",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("User Name") ?>",
                        "width": "20%",
                        data: "fstUserName",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Action ") ?>",
                        "width": "5%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-user-detail edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });
            $("#tbl_trx_detail").on("click", ".btn-delete-user-detail", function(event) {
                event.preventDefault();
                t = $("#tbl_trx_detail").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });

            $("#fstUserCode").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/user/get_user_pic',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.fstUserCode,
                                "text": value.fstUserName
                            });
                        });
                        console.log(data2);
                        return {
                            results: data2
                        };
                    },
                    cache: true,
                }
            });
            var selected_pic;
            $('#fstUserCode').on('select2:select', function(e) {
                console.log(selected_pic);
                var data = e.params.data;
                selected_pic = data;
            });

            $("#btn-add-detail").click(function(event) {
                event.preventDefault();
                t = $('#tbl_trx_detail').DataTable();
                addRow = true;
                var pic = $("#fstUserCode").val();
                if (pic == null || pic == "") {
                    $("#fstUserCode_err").html("Please select User");
                    $("#fstUserCode_err").show();
                    addRow = false;
                    return;
                } else {
                    data = t.rows().data();
                    console.log(data);
                    var valid = true;
                    $.each(data, function(i, v) {
                        if (v.fstUserCode == pic) {
                            $("#fstUserCode_err").html("Selected User is already exist!");
                            $("#fstUserCode_err").show();
                            addRow = false;
                            valid = false;
                            return false;
                        } else {
                            $("#fstUserCode_err").hide();
                        }
                    });
                    if (valid == false){
                        return;
                    }
                }
                t.row.add({
                    fin_id: 0,
                    fstUserCode: selected_pic.id,
                    fstUserName: selected_pic.text,
                    action: action
                }).draw(false);
            });
        });
    </script>
</div>

<script type="text/javascript">

	$(function() {

		<?php if($mode == "EDIT"){?>
			init_form($("#finTrxId").val());
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			//data = new FormData($("#frmBPKBTrx")[0]);
			data = $("#frmBPKBTrx").serializeArray();

			detail = new Array();
            t = $('#tbl_trx_detail').DataTable();
            datas = t.data();
            $.each(datas, function(i, v) {
                detail.push(v);
            });
            data.push({
                name: "detailuser",
                value: JSON.stringify(detail)
            });

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>master/bpkbtrxs/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>master/bpkbtrxs/ajx_edit_save";
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
										window.location.href = "<?= site_url() ?>master/bpkbtrxs/add";
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
						$("#finTrxId").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT

						//$('#fst_user_name').prop('readonly', true);
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

		/*$(".datepicker").datepicker({
			format:"yyyy-mm-dd"
		});*/
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/bpkbtrxs/add")
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
				url:"<?= site_url() ?>master/bpkbtrxs/delete/" + $("#finTrxId").val(),
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
									window.location.href = "<?= site_url() ?>master/bpkbtrxs/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fst_user_code").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					//$('#fst_user_name').prop('readonly', true);
				}
			});
		});

		/*$("#btnPrinted").click(function(e){
			$("#modal_Printed").modal("toggle");
		});

		$("#btnPrint").click(function(e){
			layoutColumn = [
				{column: "Birth date",hidden:false,id:"fdt_birthdate"},
				{column: "Birth place",hidden:false,id:"fst_birthplace"},
				{column: "Address",hidden:false,id:"fst_address"},
				{column: "Phone",hidden:false,id:"fst_phone"},
				{column: "Email",hidden:false,id:"fst_email"},
			];
			url = "<?= site_url() ?>user/get_printUser/" + $("#select-pic_R").val() + '/' + $("#select-department_R").val() + '/' + $("#select-userId_start").val() + '/' + $("#select-userId_end").val();
			MdlPrint.showPrint(layoutColumn,url);
		});*/

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/bpkbtrxs/lizt");
		});


		App.fixedSelect2();
	});


	function init_form(finTrxId){
		//alert("Init Form");
		var finTrxId = $("#finTrxId").val();
		var url = "<?=site_url()?>master/bpkbtrxs/fetch_data/" + finTrxId;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.bpkbtrx);

				$.each(resp.bpkbtrx, function(name, val){
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

				if (resp.bpkbtrx.fblIsSystemTrx == "1"){
					$("#fblIsSystemTrx").prop("checked",true);
				}else{
					$("#fblIsSystemTrx").prop("checked",false);
				}
                
                //populate Trx Detail
                $.each(resp.trxdetail, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_trx_detail').DataTable();
                    t.row.add({
                        finTrxId: val.finTrxId,
                        fstUserCode: val.fstUserCode,
                        fstUserName: val.fstUserName,
                        action: action
                    }).draw(false);
                })
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