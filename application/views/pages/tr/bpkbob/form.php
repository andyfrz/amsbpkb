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
    <h1><?= lang("BPKB Opening Balance") ?><small><?= lang("form") ?></small></h1>
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
                <form id="frmBpkbob" class="form-horizontal" action="<?= site_url() ?>trx/bpkbob/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="finId" class="col-sm-2 control-label"><?= lang("ID") ?> *</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="finId" placeholder="<?= lang("Leasing Code") ?>" name="finId" value="<?= $finId ?>"readonly>
                                <div id="finId_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fstBpkbNo" class="col-sm-2 control-label"><?= lang("BPKB No.") ?> *</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="fstBpkbNo" placeholder="<?= lang("BPKB No.") ?>" name="fstBpkbNo">
                                <div id="fstBpkbNo_err" class="text-danger"></div>
                            </div>
                            <label for="fdtBpkbDate" class="col-md-2 control-label"><?= lang("BPKB Date") ?></label>
                            <div class="col-sm-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="fdtBpkbDate" name="fdtBpkbDate"/>
                                </div>
                                <div id="fdtBpkbDate_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fstDealerCode" class="col-md-2 control-label"><?= lang("Dealer") ?></label>
                            <div class="col-md-4">
                                <select class="form-control" id="fstDealerCode" name="fstDealerCode">
                                    <?php
                                        $dealerList = $this->msdealers_model->getAllList();
                                        foreach($dealerList as $dealer){
                                            echo "<option value='$dealer->fstDealerCode'>$dealer->fstDealerName</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <label for="fstCustomerName" class="col-sm-2 control-label"><?= lang("Customer") ?></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="fstCustomerName" placeholder="<?= lang("Customer") ?>" name="fstCustomerName">
                                <div id="fstCustomerName_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fstNik" class="col-sm-2 control-label"><?= lang("NIK") ?></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="fstNik" placeholder="<?= lang("NIK") ?>" name="fstNik">
                                <div id="fstNik_err" class="text-danger"></div>
                            </div>
                            <label for="fstNpwp" class="col-sm-2 control-label"><?= lang("NPWP") ?></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="fstNpwp" placeholder="<?= lang("NPWP") ?>" name="fstNpwp">
                                <div id="fstNpwp_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="finBrandTypeId" class="col-sm-2 control-label"><?= lang("Brand Type") ?></label>
                            <div class="col-sm-4">
                                <select class="form-control" id="finBrandTypeId" name="finBrandTypeId">
                                    <?php
                                        $brandtypeList = $this->msbrandtypes_model->getAllList();
                                        foreach($brandtypeList as $brandtype){
                                            echo "<option value='$brandtype->finBrandTypeID'>$brandtype->fstBrandCode</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <label for="fstBrandCode" class="col-sm-2 control-label"><?= lang("Brand") ?></label>
                            <div class="col-sm-4">
                                <select class="form-control" id="fstBrandCode" name="fstBrandCode">
                                    <?php
                                        $brandList = $this->msbrands_model->getAllList();
                                        foreach($brandList as $brand){
                                            echo "<option value='$brand->fstBrandCode'>$brand->fstBrandName</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fstColourCode" class="col-sm-2 control-label"><?= lang("Colour") ?></label>
                            <div class="col-sm-4">
                                <select class="form-control" id="fstColourCode" name="fstColourCode">
                                    <?php
                                        $colourList = $this->mscolours_model->getAllList();
                                        foreach($colourList as $colour){
                                            echo "<option value='$colour->fstColourCode'>$colour->fstColourName</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <label for="finManufacturedYear" class="col-sm-2 control-label"><?= lang("Manufactured Year") ?></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="finManufacturedYear" placeholder="<?= lang("Manufactured Year") ?>" name="finManufacturedYear">
                                <div id="finManufacturedYear_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fstEngineNo" class="col-sm-2 control-label"><?= lang("Engine No.") ?></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="fstEngineNo" placeholder="<?= lang("Engine No.") ?>" name="fstEngineNo">
                                <div id="fstEngineNo_err" class="text-danger"></div>
                            </div>
                            <label for="fstChasisNo" class="col-sm-2 control-label"><?= lang("Chasis No.") ?></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="fstChasisNo" placeholder="<?= lang("Chasis No.") ?>" name="fstChasisNo">
                                <div id="fstChasisNo_err" class="text-danger"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="finTrxId" class="col-md-2 control-label"><?= lang("BPKB Trx") ?></label>
                            <div class="col-md-4">
                                <select class="form-control" id="finTrxId" name="finTrxId">
                                    <?php
                                        $bpkbtrxList = $this->msbpkbtrxs_model->getAllList();
                                        foreach($bpkbtrxList as $bpkbtrx){
                                            echo "<option value='$bpkbtrx->finTrxId'>$bpkbtrx->fstTrxDescription</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <label for="finWarehouseId" class="col-sm-2 control-label"><?= lang("Warehouse") ?></label>
                            <div class="col-sm-4">
                                <select class="form-control" id="finWarehouseId" name="finWarehouseId">
                                    <?php
                                        $warehouseList = $this->msbpkbwarehouse_model->getAllList();
                                        foreach($warehouseList as $warehouse){
                                            echo "<option value='$warehouse->finWarehouseId'>$warehouse->fstWarehouseName</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fstInfo" class="col-sm-2 control-label"><?= lang("Info") ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fstInfo" placeholder="<?= lang("Info") ?>" name="fstInfo">
                                <div id="fstInfo_err" class="text-danger"></div>
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

        <?php if ($mode == "EDIT") { ?>
            init_form($("#finId").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            //data = new FormData($("#frmBpkbob")[0]);
            data = $("#frmBpkbob").serializeArray();

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>trx/bpkbob/ajx_add_save";
            } else {
                url = "<?= site_url() ?>trx/bpkbob/ajx_edit_save";
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
                        $("#finId").val(data.insert_id);

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
			window.location.replace("<?=site_url()?>trx/bpkbob/add")
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
				url:"<?= site_url() ?>trx/bpkbob/delete/" + $("#finId").val(),
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
									window.location.href = "<?= site_url() ?>trx/bpkbob/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#finId").val(data.insert_id);

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
			window.location.replace("<?=site_url()?>trx/bpkbob/lizt");
		});
    });

    function init_form(finId) {
        //alert("Init Form");
        var url = "<?= site_url() ?>trx/bpkbob/fetch_data/" + finId;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.bpkb);

                $.each(resp.bpkb, function(name, val) {
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
                    $("#fdtBpkbDate").val(dateFormat(resp.bpkb.fdtBpkbDate)).datepicker("update");
                });
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