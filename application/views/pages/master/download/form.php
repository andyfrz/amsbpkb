<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script src="<?=base_url()?>bower_components/jquery/jquery.md5.js"></script>
<section class="content-header">
    <h1><?= lang("Download Master") ?><small><?= lang("") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Master Data") ?></a></li>
        <li><a href="#"><?= lang("Download Master") ?></a></li>
    </ol>
</section>

<section class="content">
<div class="row">
	<div class="col-md-12">
    <div class="box box-default">
		<div class="box-header with-border">
		  <i class="fa fa-cloud-download"></i>
		  <h3 class="box-title">Download Data Master</h3>
		</div>
		<!-- /.box-header -->
        <form id="frmDownloadMaster" class="form-horizontal" action="<?= site_url() ?>master/downloadmaster/download_api" method="POST" enctype="multipart/form-data">
            <div class="box-body">
                <div class="form-group row">
                    <div class="col-sm-12">								
                        <div class="radio">
                        <label>
                            <input type="radio" name="opsi_master" id="download_dealer" value="1" checked="">
                            Dealers
                        </label>
                        </div>
                        <div class="radio">
                        <label>
                            <input type="radio" name="opsi_master" id="download_colour" value="2">
                            Colours
                        </label>
                        </div>
                        <div class="radio">
                        <label>
                            <input type="radio" name="opsi_master" id="download_brand" value="3">
                            Brands
                        </label>
                        </div>
                        <div class="radio">
                        <label>
                            <input type="radio" name="opsi_master" id="download_brandtype" value="4">
                            Brand Types
                        </label>
                        </div>
                    </div>
                </div>
                <button type="button"  id="btnDownload" href="#" title="<?=lang("Download")?>" class="btn btn-primary btn-block"><i class="fa fa-cloud-download" aria-hidden="true"></i></button>
            </div>
        </form>
		<!-- /.box-body -->
	  </div>
	  <!-- /.box -->
	</div>
	<!-- /.col -->
</div>
</section>

<script type="text/javascript">
    $(function() {

        var master = {
            [SECURITY_NAME]:SECURITY_VALUE,
            "opsimaster": $('input[name=opsi_master]:checked').val(),
        };
        var opsi_master = $('input[name=opsi_master]:checked').val();
        $("#btnDownload").click(function(event){
            event.preventDefault();
            insertData(master);
        });

        function insertData(master){
            var dataSubmit = $("#frmDownloadMaster").serializeArray();
            var detail = new Array();
            opsimaster = master;
            $.each(opsimaster,function(i,v){
                detail.push(v);
            });

            dataSubmit.push({
                name:"detail",
                value: JSON.stringify(detail)
            });

            alert(opsi_master);

            if (opsi_master == 1){
                url =  "<?= site_url() ?>master/downloadmaster/download_dealers/";
            }else if (opsi_master == 2){
                url =  "<?= site_url() ?>master/downloadmaster/download_colours/";
            }

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
                                        alert("Success !!!")
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

    });
</script>
<script src="<?=base_url()?>bower_components/jquery/jquery.md5.js"></script>