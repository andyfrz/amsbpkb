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
    <h1><?= lang("Colour") ?><small><?= lang("form") ?></small></h1>
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
                <form id="frmColour" class="form-horizontal" action="<?= site_url() ?>master/colours/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fstColourCode" class="col-sm-2 control-label"><?= lang("Colour Code") ?> *</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fstColourCode" placeholder="<?= lang("Colour Code") ?>" name="fstColourCode" value="<?= $fstColourCode ?>">
                                <div id="fstColourCode_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fstColourName" class="col-sm-2 control-label"><?= lang("Colour Name") ?> *</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fstColourName" placeholder="<?= lang("Colour Name") ?>" name="fstColourName">
                                <div id="fstColourName_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fstGenesysColourCode" class="col-sm-2 control-label"><?= lang("Genesys Code") ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fstGenesysColourCode" placeholder="<?= lang("Genesys Code") ?>" name="fstGenesysColourCode">
                                <div id="fstGenesysColourCode_err" class="text-danger"></div>
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
            init_form($("#fstColourCode").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            //data = new FormData($("#frmColour")[0]);
            data = $("#frmColour").serializeArray();

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>master/colours/ajx_add_save";
            } else {
                url = "<?= site_url() ?>master/colours/ajx_edit_save";
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

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $('#fstColourCode').prop('readonly', true);

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
			window.location.replace("<?=site_url()?>master/colours/add")
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
				url:"<?= site_url() ?>master/colours/delete/" + $("#fstColourCode").val(),
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
									window.location.href = "<?= site_url() ?>master/colours/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fstColourCode").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fstColourCode').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/colours/lizt");
		});
    });

    function init_form(fstColourCode) {
        //alert("Init Form");
        var url = "<?= site_url() ?>master/colours/fetch_data/" + fstColourCode;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.colours);

                $.each(resp.colours, function(name, val) {
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
        $('#fstColourCode').prop('readonly', true);
    }
</script>

<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>