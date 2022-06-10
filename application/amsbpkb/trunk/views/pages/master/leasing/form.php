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
    <h1><?= lang("Leasing") ?><small><?= lang("form") ?></small></h1>
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
                <form id="frmLeasing" class="form-horizontal" action="<?= site_url() ?>master/leasingcompanies/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fstLeasingCode" class="col-sm-2 control-label"><?= lang("Leasing Code") ?> *</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fstLeasingCode" placeholder="<?= lang("Leasing Code") ?>" name="fstLeasingCode" value="<?= $fstLeasingCode ?>">
                                <div id="fstLeasingCode_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fstLeasingName" class="col-sm-2 control-label"><?= lang("Leasing Name") ?> *</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fstLeasingName" placeholder="<?= lang("Leasing Name") ?>" name="fstLeasingName">
                                <div id="fstLeasingName_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fstAddress" class="col-md-2 control-label"><?= lang("Address") ?></label>
                            <div class="col-md-10">
                                <textarea class="form-control" id="fstAddress" placeholder="<?= lang("Address") ?>" name="fstAddress"></textarea>
                                <div id="fstAddress_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fstEmail" class="col-md-2 control-label"><?= lang("Email") ?></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fstEmail" placeholder="<?= lang("Email") ?>" name="fstEmail">
                                <div id="fstEmail_err" class="text-danger"></div>
                            </div>

                            <label for="fstPhoneNo" class="col-sm-2 control-label"><?= lang("Phone") ?></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="fstPhoneNo" placeholder="<?= lang("Phone") ?>" name="fstPhoneNo">
                                <div id="fstPhoneNo_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fstPersonInCharge" class="col-sm-2 control-label"><?= lang("PIC") ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fstPersonInCharge" placeholder="<?= lang("PIC") ?>" name="fstPersonInCharge">
                                <div id="fstPersonInCharge_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fstGenesysLeasingCode" class="col-sm-2 control-label"><?= lang("Genesys Leasing Code") ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fstGenesysLeasingCode" placeholder="<?= lang("Genesys Leasing Code") ?>" name="fstGenesysLeasingCode">
                                <div id="fstGenesysLeasingCode_err" class="text-danger"></div>
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
            init_form($("#fstLeasingCode").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            //data = new FormData($("#frmLeasing")[0]);
            data = $("#frmLeasing").serializeArray();

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>master/leasingcompanies/ajx_add_save";
            } else {
                url = "<?= site_url() ?>master/leasingcompanies/ajx_edit_save";
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
                        $("#fstLeasingCode").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $('#fstLeasingName').prop('readonly', true);

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
			window.location.replace("<?=site_url()?>master/leasingcompanies/add")
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
				url:"<?= site_url() ?>master/leasingcompanies/delete/" + $("#fstLeasingCode").val(),
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
									window.location.href = "<?= site_url() ?>master/leasingcompanies/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fstLeasingCode").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fstLeasingName').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/leasingcompanies/lizt");
		});
    });

    function init_form(fstLeasingCode) {
        //alert("Init Form");
        var url = "<?= site_url() ?>master/leasingcompanies/fetch_data/" + fstLeasingCode;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.leasingcompanies);

                $.each(resp.leasingcompanies, function(name, val) {
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