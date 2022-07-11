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
    <h1><?= lang("BPKB Transfer Out") ?><small><?= lang("form") ?></small></h1>
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
                <form id="frmBpkbTransferout" class="form-horizontal" action="<?= site_url() ?>trx/bpkbtransferout/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fstTransferOutNo" class="col-sm-2 control-label"><?= lang("Transfer No") ?> *</label>
                            <div class="col-sm-4">
                                <input type="TEXT" id="fstTransferOutNo" name="fstTransferOutNo" class="form-control"  value="<?=$fstTransferOutNo?>" placeholder="PREFIX/YEARMONTH/99999" readonly /> 
                                <div id="fstTransferOutNo_err" class="text-danger"></div>
                            </div>
                            <label for="fdtTransferOutDate" class="col-md-2 control-label"><?= lang("Transfer Date") ?></label>
                            <div class="col-sm-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="fdtTransferOutDate" name="fdtTransferOutDate"/>
                                </div>
                                <div id="fdtTransferOutDate_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="finTransferType" class="col-md-2 control-label"><?=lang("Type")?> *</label>
                            <div class="col-md-4">
                                <select class="form-control" id="finTransferType" name="finTransferType">
                                    <option value='1'><?=lang("Mutasi Gudang")?></option>
                                    <option value='2'><?=lang("Request Checkout")?></option>
                                    <option value='3'><?=lang("Request Dealer")?></option>
                                    <option value='4'><?=lang("Peminjaman")?></option>
                                    <option value='5'><?=lang("Perbaikan")?></option>
                                    <option value='6'><?=lang("Proses Ulang")?></option>
                                </select>
                            </div>

                            <label for="fstReqNo" class="col-md-2 control-label"><?= lang("Request No") ?></label>
                            <div class="col-md-4">
                                <select class="form-control" id="fstReqNo" name="fstReqNo"></select>
                                <div id="fstReqNo_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="finFromWarehouseId" class="col-md-2 control-label"><?= lang("From Warehouse") ?></label>
                            <div class="col-md-4">
                                <select class="form-control" id="finFromWarehouseId" name="finFromWarehouseId">
                                    <?php
                                        $warehouseList = $this->Msbpkbwarehouse_model->getAllList();
                                        foreach($warehouseList as $warehouse){
                                            echo "<option value='$warehouse->finWarehouseId'>$warehouse->fstWarehouseName</option>";
                                        }
                                    ?>
                                </select>
                                <div id="finFromWarehouseId_err" class="text-danger"></div>
                            </div>
                            <label for="finToWarehouseId" class="col-md-2 control-label"><?= lang("To Warehouse") ?></label>
                            <div class="col-md-4">
                                <select class="form-control" id="finToWarehouseId" name="finToWarehouseId">
                                    <?php
                                        $warehouseList = $this->Msbpkbwarehouse_model->getAllList();
                                        foreach($warehouseList as $warehouse){
                                            echo "<option value='$warehouse->finWarehouseId'>$warehouse->fstWarehouseName</option>";
                                        }
                                    ?>
                                </select>
                                <div id="finToWarehouseId_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fdbDaysToWarehouse" class="col-md-2 control-label"><?=lang("Days To Warehouse")?></label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="fdbDaysToWarehouse" name="fdbDaysToWarehouse" style="width:50px" value="0"/>
								<div id="fdbDaysToWarehouse_err" class="text-danger"></div>
							</div>
							<label class="col-md-1 control-label" style="text-align:left;padding-left:0px"><?=lang("Days")?> </label>
							<div class="col-md-6" style='text-align:right'>
								<button id="btn-add-detail" class="btn btn-primary btn-sm">
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
									<?=lang("Add Bpkb")?>
								</button>
							</div>
						</div>

                        <table id="tbl_out_detail" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
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


<div id="mdlOutDetail" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:650px">
        <!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add Transfer Detail")?></h4>
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
                                    <button id="btn-add-out" type="button" class="btn btn-primary btn-sm text-center" style="width:15%" ><?=lang("Add")?></button>
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

        var mdlOutDetail = {
            show:function(data){
                mdlOutDetail.clear();
                console.log(data);
                //alert(data.dfstBpkbNo);

                if (typeof(data) == "undefined"){
                    $("#mdlOutDetail").modal("show");
                    selecteddetail = null;					
                    return;
                }
                $("#finRecId").val(data.finRecId);
                $("#fstBpkbNo").val(data.fstBpkbNo);
                $("#fstNotes").val("");							
                $("#mdlOutDetail").modal({
                    backdrop:"static",
                });
                

            },
            hide:function(){
                $("#mdlOutDetail").modal("hide");
            },
            clear:function(){
                $("#fstBpkbNo").val("");
                $("#fstNotes").val("");
            },
            checkin:function(){								
                mdlOutDetail.clear();
            }			
        };

        $(function() {

            $("#btn-add-out-detail").click(function(event) {
                event.preventDefault();
                $("#mdlOutDetail").modal('show');
            });

            $("#btn-add-out").click(function(event) {
                event.preventDefault();
                var dataPost = {
                    [SECURITY_NAME]:SECURITY_VALUE,
                    "fstBpkbNo": $("#fstBpkbNo").val(),
                    "finWarehouseId":$("#finFromWarehouseId").val(),
                    "fstReqNo":$("#fstReqNo").val(),
                };
                t = $('#tbl_out_detail').DataTable();
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
                    url:"<?= site_url() ?>trx/bpkbtransferout/valid/",
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
                            mdlOutDetail.hide();
                        }
                    }
                })
            });
        });
    </script>
</div>


<script type="text/javascript" info="INIT">
    $(function(){
        $("#fdtTransferOutDate").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");
        $("#fstReqNo").val(null).change();
        $("#finFromWarehouseId").val(null).change();
        $("#finToWarehouseId").val(null).change();
    
        $("#tbl_out_detail").DataTable({
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
                        action = "<a class='btn-delete-out-detail edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                        return action;
                    },
                    "sortable": false,
                    "className": "dt-body-center text-center"
                }
            ],
        }).on('draw',function(){
            $('.btn-delete-out-detail').confirmation({
                title: "<?=lang('Delete record?')?>",
                rootSelector: '.btn-delete-out-detail',
                // other options
            });	

        })

        $("#tbl_out_detail").on("click", ".btn-delete-out-detail", function(event) {
            event.preventDefault();
            t = $("#tbl_out_detail").DataTable();
            var trRow = $(this).parents('tr');
            t.row(trRow).remove().draw();
        });
    });
</script>

<script type="text/javascript" info="EVENT">
    $(function() {

        <?php if ($mode == "EDIT") { ?>
            init_form($("#fstTransferOutNo").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {

            event.preventDefault();
            data = $("#frmBpkbTransferout").serializeArray();
            detail = new Array();		
            t = $('#tbl_out_detail').DataTable();
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
                url = "<?= site_url() ?>trx/bpkbtransferout/ajx_add_save";
            } else {
                url = "<?= site_url() ?>trx/bpkbtransferout/ajx_edit_save";
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
                        $("#fstTransferOutNo").val(data.insert_id);

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
			window.location.replace("<?=site_url()?>trx/bpkbtransferout/add")
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
				url:"<?= site_url() ?>trx/bpkbtransferout/delete/" + $("#fstTransferOutNo").val(),
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
									window.location.href = "<?= site_url() ?>trx/bpkbtransferout/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fstTransferOutNo").val(data.insert_id);

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
			window.location.replace("<?=site_url()?>trx/bpkbtransferout/lizt");
		});

        $("#btn-add-detail").click(function(event){
			event.preventDefault();
			mdlOutDetail.show();
		});

        $("#finTransferType").change(function(event){
            event.preventDefault();
            var newVal = $(this).val();
            if (confirm('Mengganti type akan mengosongkan Data detail, Ganti ???')) {
                $.data(this, 'val', newVal); 
                //$("#fstReqNo").empty().trigger("change");
                getRequest(function(resp){
                    $("#tbl_out_detail").DataTable().clear().draw(false);
                });	
            } else {
                $(this).val($.data(this, 'val')); //set back
                return; 
            }
			
        });

        /*$("#fstReqNo").select2({
            ajax: {
                url: '<?=site_url()?>trx/bpkbtransferout/get_request_list/',
                delay: 250, //milliseconds
                data: function(params){
                    params.finTransferType = $("#finTransferType").val();
                    return params;
                },
                processResults: function (resp) {
                    var data = resp.data;
                    return {
                        results: $.map(data,function(obj){
                            obj.id = obj.fstReqNo;
                            obj.text = obj.fstReqNo;
                            return obj;
                        })
                    };
                }
            },
            minimumInputLength: 3,
        });*/

    });

    function getRequest(callback){
        if ($("#finTransferType").val() == null){
            return;
        }
        App.getValueAjax({
            site_url:"<?=site_url()?>",
            model:"trbpkbtransferout_model",
            func:"get_RequestList",
            params:[$("#finTransferType").val()],
            callback:function(resp){
                var requestList = resp;
                $("#fstReqNo").empty();
                $.each(requestList ,function(i,v){
                    $("#fstReqNo").append("<option value='"+v.fstReqNo+"'>"+ v.fstReqNo +"</option>")
                });
                $("#fstReqNo").val(null);
                //$("#tbldetails").DataTable().clear().draw(false);				
                callback(resp);
            }
        });
    }

    function init_form(fstTransferOutNo) {
        //alert("Init Form");
        //var fstTransferOutNo = $.md5(fstTransferOutNo);
        var url = "<?= site_url() ?>trx/bpkbtransferout/fetch_data/" + fstTransferOutNo;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.transferOut);
                dataH = resp.transferOut;
			    dataD = resp.transferOutDetail;

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

                    //$("#fstReqNo").val(dataH.fstReqNo).trigger("change.select2");
                    $("#finFromWarehouseId").val(dataH.finFromWarehouseId).trigger("change.select2");
                    $("#finToWarehouseId").val(dataH.finToWarehouseId).trigger("change.select2");
                    $("#fdtTransferOutDate").val(dateFormat(dataH.fdtTransferOutDate)).datepicker("update");
                });

                getRequest(function(resp){
                    //console.log($("#fin_lpbpurchase_id option[value='"+ dataH.fin_lpbpurchase_id +"']").length);
                    App.addOptionIfNotExist("<option value='"+ dataH.fstReqNo +"'>" + dataH.fstReqNo + "</option>","fstReqNo");
                    $("#fstReqNo").val(dataH.fstReqNo).trigger("change.select2");
                });

                t = $('#tbl_out_detail').DataTable();
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