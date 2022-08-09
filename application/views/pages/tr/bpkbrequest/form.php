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
    <h1><?= lang("BPKB Request") ?><small><?= lang("form") ?></small></h1>
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
                    <?php if ($mode != "VIEW") { ?>
                    <div class="btn-group btn-group-sm  pull-right">					
                        <a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
                        <a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                        <a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        <a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
                    </div>
                    <?php } ?>
                </div>
                <!-- end box header -->

                <!-- form start -->
                <form id="frmBpkbRequest" class="form-horizontal" action="<?= site_url() ?>trx/bpkbrequest/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fstReqNo" class="col-sm-2 control-label"><?= lang("Request No") ?></label>
                            <div class="col-sm-4">
                                <input type="TEXT" id="fstReqNo" name="fstReqNo" class="form-control"  value="<?=$fstReqNo?>" placeholder="PREFIX/YEARMONTH/99999" readonly /> 
                                <div id="fstReqNo_err" class="text-danger"></div>
                            </div>
                            <label for="fdtReqDate" class="col-md-2 control-label"><?= lang("Request Date") ?></label>
                            <div class="col-sm-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="fdtReqDate" name="fdtReqDate"/>
                                </div>
                                <div id="fdtReqDate_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fstDealerCode" class="col-md-2 control-label"><?= lang("Dealer") ?></label>
                            <div class="col-md-4">
                                <?php
                                $user = $this->aauth->user();
                                $dealerActive = $user->fstDealerCode;
                                $disabledSelect = ($dealerActive == "") ? "" : "disabled";
                                ?>
                                <select class="form-control select2" id="fstDealerCode" name="fstDealerCode" <?= $disabledSelect ?>>
                                    <?php
                                        $dealerList = $this->msdealers_model->getAllList();
                                        foreach($dealerList as $dealer){
                                            $isActive = ($dealer->fstDealerCode == $dealerActive) ? "selected" : "";
                                            echo "<option value=" . $dealer->fstDealerCode . " $isActive >" . $dealer->fstDealerCode. "-" .$dealer->fstDealerName . "</option>";
                                            //echo "<option value='$dealer->fstDealerCode'>$dealer->fstDealerName</option>";
                                        }
                                    ?>
                                </select>
                                <div id="fstDealerCode_err" class="text-danger"></div>
                            </div>
                            <label for="hfinTrxId" class="col-md-2 control-label"><?= lang("BPKB Source") ?></label>
                            <div class="col-md-4">
                                <select class="form-control" id="hfinTrxId" name="hfinTrxId">
                                    <?php
                                        $bpkbtrxList = $this->msbpkbtrxs_model->getAllList();
                                        foreach($bpkbtrxList as $bpkbtrx){
                                            echo "<option value='$bpkbtrx->finTrxId'>$bpkbtrx->fstTrxDescription</option>";
                                        }
                                    ?>
                                </select>
                                <div id="hfinTrxId_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="finTransferType" class="col-md-2 control-label"><?=lang("Type")?></label>
                            <div class="col-md-4">
                                <select class="form-control" id="finTransferType" name="finTransferType">
                                    <option value='2'><?=lang("Request Checkout")?></option>
                                    <option value='3'><?=lang("Request Dealer")?></option>
                                    <option value='4'><?=lang("Peminjaman")?></option>
                                    <option value='5'><?=lang("Perbaikan")?></option>
                                    <option value='6'><?=lang("Proses Ulang")?></option>
                                </select>
                            </div>
                            <?php if ($mode != "VIEW") { ?>
							<div class="col-md-6" style='text-align:right'>
								<button id="btn-add-detail" class="btn btn-primary btn-sm">
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
									<?=lang("Add Bpkb")?>
								</button>
							</div>
                            <?php } ?>	
						</div>

                        <table id="tbl_req_detail" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
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

<div id="MdlBpkbReq" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:1300px">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= lang("Query Data BPKB") ?></h4>
			</div>
			<div class="modal-body">
                <input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">				
				<form class="form-horizontal">
					<div class='form-group'>
                    	<label for="fstCustomerName" class="col-sm-1 control-label">Customer</label>
						<div class="col-sm-5">
							<input type="text" id="fstCustomerName" class="form-control"></input>
						</div>
						<label for="fstNik" class="col-sm-1 control-label">NIK</label>
						<div class="col-sm-2">
                            <input type="text" id="fstNik" class="form-control"></input>
						</div>
                        <label for="fstBpkbNo" class="col-sm-1 control-label">BPKB No.</label>
						<div class="col-sm-2">
                            <input type="text" id="fstBpkbNo" class="form-control"></input>
						</div>
					</div>
                    <div class='form-group'>
                    	<label for="fstBrandName" class="col-sm-1 control-label">Brand Type</label>
						<div class="col-sm-2">
							<input type="text" id="fstBrandName" class="form-control"></input>
						</div>
						<label for="fstEngineNo" class="col-sm-1 control-label">Engine No.</label>
						<div class="col-sm-2">
                            <input type="text" id="fstEngineNo" class="form-control"></input>
						</div>
                        <label for="fstChasisNo" class="col-sm-1 control-label">Chasis No.</label>
						<div class="col-sm-2">
                            <input type="text" id="fstChasisNo" class="form-control"></input>
						</div>
                        <div class="col-sm-3 text-right">
							<a id="btnShowData" href="#" class="btn btn-primary">Query Data</a>
						</div>
					</div>					
				</form>

                <table id="dtblBpkb" style="width:100%"></table>
                
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var selected_bpkb;

		var MdlBpkbReq = {
			show:function(data){
				MdlBpkbReq.clear();
				console.log(data);


				if (typeof(data) == "undefined"){
					$("#MdlBpkbReq").modal("show");					
					return;
				}								
				$("#MdlBpkbReq").modal({
					backdrop:"static",
				});
				

			},
			hide:function(){
				$("#MdlBpkbReq").modal("hide");
			},
			clear:function(){
				
				$("#fstCustomerName").val("");
				$("#fstNik").val("");
				$("#fstBpkbNo").val("");
				$("#fstBrandName").val("");
				$("#fstEngineNo").val("");
				$("#fstChasisNo").val("");
				t = $("#dtblBpkb").DataTable();
				t.clear().draw();
			},
			checkin:function(){
				//selectedDetail								
				MdlBpkbReq.clear();
			}			
		};

        $(function(){

            $('#dtblBpkb').on('preXhr.dt', function ( e, settings, data ) {
                //add aditional data post on ajax call
                //data.sessionId = "TEST SESSION ID";
                data.optionSearch = $('#selectSearch').val();
                //data.dateLog = $("#date-log").val();
            }).DataTable({
                columns:[
                    {"title" : "id","width": "5%","data":"dfinId","sortable":false,visible:false},
                    {"title" : "Trx Id","width": "10%","data":"dfinTrxId","sortable":true,visible:false},
                    {"title" : "BPKB No","width": "10%","data":"dfstBpkbNo","sortable":true},
                    {"title" : "BPKB Date","width": "10%","data":"dfdtBpkbDate","sortable":true},
                    {"title" : "NIK","width": "10%","data":"dfstNik","sortable":true},
                    {"title" : "Customer","width": "10%","data":"dfstCustomerName","sortable":true},
                    {"title" : "Engine No.","width": "10%","data":"dfstEngineNo","sortable":true},
                    {"title" : "Chasis No.","width": "10%","data":"dfstChasisNo","sortable":true},
                    {
                        "title": "Action",
                        "width": "5%",
                        render: function(data, type, row) {
                            action = "<a class='btn-add-bpkb' href='#'><i class='fa fa-cart-plus'></i></a>&nbsp;";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    },                				
                ],			
                processing: false,
                serverSide: false,
            }).on("click",".btn-add-bpkb",function(e){
                e.preventDefault();
                t = $("#dtblBpkb").DataTable();
                var trRow = $(this).parents('tr');
                selectedBpkb  = t.row(trRow);
                var data = t.row(trRow).data();
                mdlRequest.show(data);
                //mdlAddItems.show(data);
                //ischeckin(data.dfinId);		
            });

            $("#btnShowData").click(function(e){
                e.preventDefault();
                var dataPost = {
                    [SECURITY_NAME]:SECURITY_VALUE,
                    "fstCustomerName": $("#fstCustomerName").val(),
                    "fstNik":$("#fstNik").val(),
                    "fstBpkbNo":$("#fstBpkbNo").val(),
                    "fstBrandName":$("#fstBrandName").val(),
                    "fstEngineNo":$("#fstEngineNo").val(),
                    "fstChasisNo":$("#fstChasisNo").val(),
                };

                var t = $('#dtblBpkb').DataTable();
                blockUIOnAjaxRequest();
                $.ajax({
                    url:"<?=site_url()?>trx/bpkbrequest/ajxBpkbData",
                    method:"POST",
                    data:dataPost,
                }).done(function(resp){
                    if (resp.status == "SUCCESS"){
                        t.clear();
                        records = resp.data;
                        $.each(records, function(i,record){
                            var dataRow = {
                                dfinId:record.finId,
                                dfinTrxId:record.finTrxId,
                                dfstBpkbNo:record.fstBpkbNo,
                                dfdtBpkbDate:App.dateFormat(record.fdtBpkbDate),
                                dfstNik:record.fstNik,
                                dfstCustomerName:record.fstCustomerName,
                                dfstEngineNo:record.fstEngineNo,
                                dfstChasisNo:record.fstChasisNo
                            };
                            t.row.add(dataRow);
                        });
                        t.draw(false);
                    }
                });
            });
        });

	</script>
</div>



<div id="mdlReqDetail" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:650px">
        <!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add Request Detail")?></h4>
			</div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
                                <form class="form-horizontal ">
                                    <input type='hidden' id='finRecId'/>
                                    <input type='hidden' id='finTrxId'/>
                                    <div class="form-group">
										<label for="rfstBpkbNo" class="col-md-3 control-label"><?=lang("BPKB No")?></label>
										<div class="col-md-9">
											<input id="rfstBpkbNo" class="form-control"></select>
											<div id="rfstBpkbNo_err" class="text-danger"></div>
										</div>
									</div>
                                    <div class="form-group">
										<label for="rfstNotes" class="col-md-3 control-label"><?=lang("Note")?></label>
										<div class="col-md-9">
											<textarea type="text" class="form-control" id="rfstNotes" rows="3"></textarea>
										</div>
									</div>
                                </form>
                                <div class="modal-footer" style="width:100%;padding:10px" class="text-center">
                                    <button id="btn-add-req" type="button" class="btn btn-primary btn-sm text-center" style="width:15%" ><?=lang("Add")?></button>
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
        //var action = '<a class="btn-edit" href="#" data-toggle="" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp; <a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
        var selected_items;

        var mdlRequest = {
            show:function(data){
                mdlRequest.clear();
                console.log(data);
                //alert(data.dfstBpkbNo);

                if (typeof(data) == "undefined"){
                    $("#mdlReqDetail").modal("show");
                    selectedBpkb = null;					
                    return;
                }
                $("#finRecId").val(data.dfinId);
                $("#finTrxId").val(data.dfinTrxId);
                $("#rfstBpkbNo").val(data.dfstBpkbNo);
                $("#rfstNotes").val("");							
                $("#mdlReqDetail").modal({
                    backdrop:"static",
                });
                

            },
            hide:function(){
                $("#mdlReqDetail").modal("hide");
            },
            clear:function(){
                $("#rfstBpkbNo").val("");
                $("#rfstNotes").val("");
            },
            checkin:function(){								
                mdlRequest.clear();
            }			
        };
        $(function() {
            $("#btn-add-req-detail").click(function(event) {
                event.preventDefault();
                $("#mdlReqDetail").modal('show');
                $("#fstUserCode").empty();
            });

            $("#btn-add-req").click(function(event) {
                event.preventDefault();
                var dataPost = {
                    [SECURITY_NAME]:SECURITY_VALUE,
                    "fstDealerCode": $("#fstDealerCode").val(),
                    "fstBpkbNo": $("#rfstBpkbNo").val(),
                    "finTrxId": $("#hfinTrxId").val(),
                };
                t = $('#tbl_req_detail').DataTable();
                addRow = true;
                var bpkb = $("#rfstBpkbNo").val();
                var notes = $("#rfstNotes").val();
                if (bpkb == null || bpkb == "") {
                    $("#rfstBpkbNo_err").html("Please entry BPKB No");
                    $("#rfstBpkbNo_err").show();
                    addRow = false;
                    return;
                } else {
                    data = t.rows().data();
                    console.log(data);
                    var valid = true;
                    $.each(data, function(i, v) {
                        if (v.fstBpkbNo == bpkb) {
                            $("#rfstBpkbNo_err").html("BPKB sudah ada di table detail !!!");
                            $("#rfstBpkbNo_err").show();
                            addRow = false;
                            valid = false;
                            return false;
                        } else {
                            $("#rfstBpkbNo_err").hide();
                        }
                    });
                    if (valid == false){
                        return;
                    }
                }
                var dbpkbtrx= $("#finTrxId").val();
                var hbpkbtrx= $("#hfinTrxId").val();
                if(dbpkbtrx != hbpkbtrx ){
                    alert("BPKB Trx tidak sesuai !!!")
                    addRow = false;
                    valid = false;
                    return false;
                }
               
                if ($("#fstDealerCode").val() != "" || $("#fstDealerCode").val() != null){
                    $.ajax({			
                        url:"<?= site_url() ?>trx/bpkbrequest/valid/",
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
                                mdlRequest.hide();
                            }
                        }
                    })
                }else{
                    t.row.add({
                        finRecId: 0,
                        fstBpkbNo: bpkb,
                        fstNotes: notes,
                        action: action
                    }).draw(false);
                    mdlRequest.hide();
                }
            });
        });
    </script>
</div>

<script type="text/javascript">

    function ischeckin(id){
		var dataSubmit = [];
		dataSubmit.push({
			name : SECURITY_NAME,
			value: SECURITY_VALUE,
		});

		$.ajax({			
			//url:"<=$ischeckin?>" + id,
			method:"POST",
			data:dataSubmit,
			success:function(resp){
				if (resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "NOT READY"){
					mdlRequest.show(id);
				}
			}
		})
	}

</script>


<script type="text/javascript" info="INIT">
    $(function(){
        $("#fdtReqDate").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");
        //$("#fstDealerCode").val(null).change();

        $("#tbl_req_detail").DataTable({
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

                <?php if ($mode != "VIEW") { ?>
                {
                    "title": "<?= lang("Action ") ?>",
                    "width": "5%",
                    render: function(data, type, row) {
                        action = "<a class='btn-delete-req-detail edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                        return action;
                    },
                    "sortable": false,
                    "className": "dt-body-center text-center"
                }
                <?php } ?>	
            ],
        }).on('draw',function(){
            $('.btn-delete-req-detail').confirmation({
                title: "<?=lang('Delete record?')?>",
                rootSelector: '.btn-delete-req-detail',
                // other options
            });	

        })

        $("#tbl_req_detail").on("click", ".btn-delete-req-detail", function(event) {
            event.preventDefault();
            t = $("#tbl_req_detail").DataTable();
            var trRow = $(this).parents('tr');
            t.row(trRow).remove().draw();
        });
    });
</script>

<script type="text/javascript" info="EVENT">
    $(function() {

        <?php if ($mode != "ADD") { ?>
            init_form($("#fstReqNo").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            //data = new FormData($("#frmBpkbRequest")[0]);
            data = $("#frmBpkbRequest").serializeArray();
            detail = new Array();		
            t = $('#tbl_req_detail').DataTable();
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
                url = "<?= site_url() ?>trx/bpkbrequest/ajx_add_save";
            } else {
                url = "<?= site_url() ?>trx/bpkbrequest/ajx_edit_save";
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
                        $("#fstReqNo").val(data.insert_id);

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
			window.location.replace("<?=site_url()?>trx/bpkbrequest/add")
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
				url:"<?= site_url() ?>trx/bpkbrequest/delete/" + $("#fstReqNo").val(),
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
									window.location.href = "<?= site_url() ?>trx/bpkbrequest/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fstReqNo").val(data.insert_id);

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
			window.location.replace("<?=site_url()?>trx/bpkbrequest/lizt");
		});

        $("#btn-add-detail").click(function(event){
			event.preventDefault();
            if (($("#hfinTrxId").val() =='1' || $("#hfinTrxId").val() =='2' || $("#hfinTrxId").val() =='3') && ($("#fstDealerCode").val() == null || $("#fstDealerCode").val() =='')){
                alert("Dealer harus diisi !!!")
            }else{
                MdlBpkbReq.show();
            }

		});


    });

    function init_form(fstReqNo) {
        //alert("Init Form");
        //var fstReqNo = $.md5(fstReqNo);
        var mode = "<?=$mode?>";		
        var url = "<?= site_url() ?>trx/bpkbrequest/fetch_data/" + fstReqNo;
        if (mode != "ADD"){	
            $.ajax({
                type: "GET",
                url: url,
                success: function(resp) {
                    console.log(resp.request);
                    dataH = resp.request;
                    dataD = resp.requestDetail;

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
                        $("#hfinTrxId").val(dataH.finTrxId).trigger("change.select2");
                        $("#fstDealerCode").val(dataH.fstDealerCode).trigger("change.select2");
                        $("#fdtReqDate").val(dateFormat(dataH.fdtReqDate)).datepicker("update");
                    });

                    t = $('#tbl_req_detail').DataTable();
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
    }
</script>

<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>
<!-- hash md5 -->
<script src="<?=base_url()?>bower_components/jquery/jquery.md5.js"></script>