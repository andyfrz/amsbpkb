<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">

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
    <h1><?= lang("BPKB Transfer IN") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Menus") ?></a></li>
        <li class="active title"><?= $title ?></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title title"><?= $title ?></h3>
                    <div class="btn-group btn-group-sm  pull-right">					
                        <a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
                        <a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                        <a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        <a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
                    </div>
                </div>
                <!-- end box header -->

                <!-- form start -->
                <form id="frmBpkbTransferin" class="form-horizontal" action="<?= site_url() ?>trx/bpkbtransferin/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fstTransferInNo" class="col-sm-2 control-label"><?= lang("Transfer IN No") ?></label>
                            <div class="col-sm-4">
                                <input type="TEXT" id="fstTransferInNo" name="fstTransferInNo" class="form-control"  value="<?=$fstTransferInNo?>" placeholder="PREFIX/YEARMONTH/99999" readonly /> 
                                <div id="fstTransferInNo_err" class="text-danger"></div>
                            </div>
                            <label for="fdtTransferInDate" class="col-md-2 control-label"><?= lang("Transfer Date") ?></label>
                            <div class="col-sm-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="fdtTransferInDate" name="fdtTransferInDate"/>
                                </div>
                                <div id="fdtTransferInDate_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fstTransferOutNo" class="col-md-2 control-label"><?=lang("Transfer OUT")?></label>
                            <div class="col-md-4">
                                <select class="form-control" id="fstTransferOutNo" name="fstTransferOutNo">
                                </select>
                                <div id="fstTransferOutNo_err" class="text-danger"></div>
                            </div>

                            <label for="finWarehouseId" class="col-md-2 control-label"><?= lang("Warehouse") ?></label>
                            <div class="col-md-4">
                                <select class="form-control" id="finWarehouseId" name="finWarehouseId">
                                    <?php
                                        $warehouseList = $this->msbpkbwarehouse_model->getAllList();
                                        foreach($warehouseList as $warehouse){
                                            echo "<option value='$warehouse->finWarehouseId'>$warehouse->fstWarehouseName</option>";
                                        }
                                    ?>
                                </select>
                                <div id="finWarehouseId_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
							<div class="col-md-12" style='text-align:right'>
								<button id="btn-add-detail" class="btn btn-primary btn-sm">
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
									<?=lang("Add Bpkb")?>
								</button>
							</div>
						</div>

                        <table id="tbl_in_detail" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
					    <br>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <textarea class="form-control" id="fstMemo" placeholder="<?= lang("Memo") ?>" name="fstMemo" rows="5" style="resize:none"></textarea>
                                <div id="fstMemo_err" class="text-danger"></div>
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


<div id="mdlInDetail" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:650px">
        <!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add Transfer In Detail")?></h4>
			</div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
                                <form class="form-horizontal ">
                                    <input type='hidden' id='finRecId'/>
                                    <div class="form-group">
										<label for="fstBpkbNo" class="col-md-3 control-label"><?=lang("BPKB No")?></label>
										<div class="col-md-9">
											<input id="fstBpkbNo" class="form-control"></select>
											<div id="fstBpkbNo_err" class="text-danger"></div>
										</div>
									</div>
                                    <div class="form-group">
										<label for="fstNotes" class="col-md-3 control-label"><?=lang("Note")?></label>
										<div class="col-md-9">
											<textarea type="text" class="form-control" id="fstNotes" rows="3"></textarea>
										</div>
									</div>
                                </form>
                                <div class="modal-footer" style="width:100%;padding:10px" class="text-center">
                                    <button id="btn-add-in" type="button" class="btn btn-primary btn-sm text-center" style="width:15%" ><?=lang("Add")?></button>
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
        var selected_items;

        var mdlInDetail = {
            show:function(data){
                mdlInDetail.clear();
                console.log(data);
                //alert(data.dfstBpkbNo);

                if (typeof(data) == "undefined"){
                    $("#mdlInDetail").modal("show");
                    selecteddetail = null;					
                    return;
                }
                $("#finRecId").val(data.finRecId);
                $("#fstBpkbNo").val(data.fstBpkbNo);
                $("#fstNotes").val("");							
                $("#mdlInDetail").modal({
                    backdrop:"static",
                });
                

            },
            hide:function(){
                $("#mdlInDetail").modal("hide");
            },
            clear:function(){
                $("#fstBpkbNo").val("");
                $("#fstNotes").val("");
            },
            checkin:function(){								
                mdlInDetail.clear();
            }			
        };

        $(function() {

            $("#btn-add-in-detail").click(function(event) {
                event.preventDefault();
                $("#mdlInDetail").modal('show');
            });

            $("#btn-add-in").click(function(event) {
                event.preventDefault();
                var dataPost = {
                    [SECURITY_NAME]:SECURITY_VALUE,
                    "fstBpkbNo": $("#fstBpkbNo").val(),
                    "fstTransferOutNo":$("#fstTransferOutNo").val(),
                };
                t = $('#tbl_in_detail').DataTable();
                addRow = true;
                var bpkb = $("#fstBpkbNo").val();
                var notes = $("#fstNotes").val();
                if (bpkb == null || bpkb == "") {
                    $("#fstBpkbNo_err").html("Please entry BPKB No");
                    $("#fstBpkbNo_err").show();
                    addRow = false;
                    return;
                } else {
                    data = t.rows().data();
                    console.log(data);
                    var valid = true;
                    $.each(data, function(i, v) {
                        if (v.fstBpkbNo == bpkb) {
                            $("#fstBpkbNo_err").html("BPKB No is already exist!");
                            $("#fstBpkbNo_err").show();
                            addRow = false;
                            valid = false;
                            return false;
                        } else {
                            $("#fstBpkbNo_err").hide();
                        }
                    });
                    if (valid == false){
                        return;
                    }
                }

                $.ajax({			
                    url:"<?= site_url() ?>trx/bpkbtransferin/valid/",
                    method:"POST",
                    data:dataPost,
                    success:function(resp){
                        if (resp.message != ""){
                            alert(resp.message);
                            addRow = false;
                            valid = false;
                            return false;
                        }

                        if (resp.status == "VALID"){
                            t.row.add({
                                finRecId: 0,
                                fstBpkbNo: bpkb,
                                fstNotes: notes,
                                action: action
                            }).draw(false);
                            mdlInDetail.hide();
                        }
                    }
                })
                /*var dbpkbtrx= $("#finTrxId").val();
                var hbpkbtrx= $("#hfinTrxId").val();
                if(dbpkbtrx != hbpkbtrx ){
                    alert("BPKB Trx tidak sesuai !!!")
                    addRow = false;
                    valid = false;
                    return false;
                }*/
            });
        });
    </script>
</div>


<script type="text/javascript" info="INIT">
    $(function(){
        $("#fdtTransferOutDate").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");
        $("#fstTransferOutNo").val(null).change();
        $("#finWarehouseId").val(null).change();
    
        $("#tbl_in_detail").DataTable({
            searching: false,
            paging: false,
            info: false,
            columns: [{
                    "title": "<?= lang("ID ") ?>",
                    "width": "5%",
                    data: "finRecId",
                    visible: false
                },
                {
                    "title": "<?= lang("BPKB No") ?>",
                    "width": "20%",
                    data: "fstBpkbNo",
                    visible: true,
                },
                {
                    "title": "<?= lang("Note") ?>",
                    "width": "40%",
                    data: "fstNotes",
                    visible: true,
                },
                {
                    "title": "<?= lang("Action ") ?>",
                    "width": "5%",
                    render: function(data, type, row) {
                        action = "<a class='btn-delete-in-detail edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                        return action;
                    },
                    "sortable": false,
                    "className": "dt-body-center text-center"
                }
            ],
        }).on('draw',function(){
            $('.btn-delete-in-detail').confirmation({
                title: "<?=lang('Delete record?')?>",
                rootSelector: '.btn-delete-in-detail',
                // other options
            });	

        })

        $("#tbl_in_detail").on("click", ".btn-delete-in-detail", function(event) {
            event.preventDefault();
            t = $("#tbl_in_detail").DataTable();
            var trRow = $(this).parents('tr');
            t.row(trRow).remove().draw();
        });
    });
</script>

<script type="text/javascript" info="EVENT">
    $(function() {

        <?php if ($mode == "EDIT") { ?>
            init_form($("#fstTransferInNo").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            data = $("#frmBpkbTransferin").serializeArray();
            detail = new Array();		
            t = $('#tbl_in_detail').DataTable();
            datas = t.data();

            $.each(datas,function(i,v){
                detail.push(v);
            });

            data.push({
                name:"detail",
                value: JSON.stringify(detail)
            });

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>trx/bpkbtransferin/ajx_add_save";
            } else {
                url = "<?= site_url() ?>trx/bpkbtransferin/ajx_edit_save";
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
                success: function(resp) {
                    if (resp.message != "") {
                        $.alert({
                            title: 'Message',
                            content: resp.message,
                            buttons: {
                                OK: function() {
                                    if (resp.status == "SUCCESS") {
                                        $("#btnNew").trigger("click");
                                        return;
                                    }
                                },
                            }
                        });
                    }

                    if (resp.status == "VALIDATION_FORM_FAILED") {
                        //Show Error
                        errors = resp.data;
                        for (key in errors) {
                            $("#" + key + "_err").html(errors[key]);
                        }
                    } else if (resp.status == "SUCCESS") {
                        data = resp.data;
                        $("#fstTransferInNo").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        //$('#fstLeasingName').prop('readonly', true);

                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });

		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>trx/bpkbtransferin/add")
		});

		$("#btnDelete").confirmation({
			title:"<?=lang("Delete data ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
			$.ajax({
				url:"<?= site_url() ?>trx/bpkbtransferin/delete/" + $("#fstTransferInNo").val(),
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
									window.location.href = "<?= site_url() ?>trx/bpkbtransferin/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fstTransferInNo").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					//$('#fstLeasingName').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>trx/bpkbtransferin/lizt");
		});

        $("#btn-add-detail").click(function(event){
			event.preventDefault();
			mdlInDetail.show();
		});

        /*$("#fstTransferOutNo").change(function(event){
			event.preventDefault();
            var warehouse = $( "#fstTransferOutNo option:selected" ).text();
            alert(warehouse);
            $("#finWarehouseId").val(warehouse).change();	
		});*/

        $("#fstTransferOutNo").select2({
            width: '100%',
            ajax: {
                url: '<?=site_url()?>trx/bpkbtransferin/get_outpending_list/',
                dataType: 'json',
                delay: 250,
                processResults: function (data){
                    items = [];
                    data = data.data;
                    $.each(data,function(index,value){
                        items.push({
                            "id" : value.fstTransferOutNo,
                            "text" : value.fstTransferOutNo,
                            "finToWarehouseId" : value.finToWarehouseId
                        });
                    });
                    console.log(items);
                    return {
                        results: items
                    };
                },
                cache: true,
            }
        }).on("select2:select",function(e){
            data = e.params.data;
			$("#finWarehouseId").val(data.finToWarehouseId);
        });


    });

    function init_form(fstTransferInNo) {
        //alert("Init Form");
        //var fstTransferInNo = $.md5(fstTransferInNo);
        var url = "<?= site_url() ?>trx/bpkbtransferin/fetch_data/" + fstTransferInNo;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.transferIn);
                dataH = resp.transferIn;
			    dataD = resp.transferInDetail;

                $.each(dataH, function(name, val) {
                    var $el = $('[name="' + name + '"]'),
                        type = $el.attr('type');
                    switch (type) {
                        case 'checkbox':
                            $el.filter('[value="' + val + '"]').attr('checked', 'checked');
                            break;
                        case 'radio':
                            $el.filter('[value="' + val + '"]').attr('checked', 'checked');
                            break;
                        default:
                            $el.val(val);
                            console.log(val);
                    }

                });

                //$("#finWarehouseId").val(dataH.finWarehouseId).trigger("change.select2");

                $("#fdtTransferInDate").val(dateFormat(dataH.fdtTransferInDate)).datepicker("update");

				var newOption = new Option(dataH.fstTransferOutNo, dataH.fstTransferOutNo, true, true);
                $("#fstTransferOutNo").append(newOption).trigger('change');

                t = $('#tbl_in_detail').DataTable();
                $.each(dataD,function(i,row){				
                    t.row.add(row);
                });
                t.draw(false);
            },

            error: function(e) {
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
<!-- hash md5 -->
<script src="<?=base_url()?>bower_components/jquery/jquery.md5.js"></script>