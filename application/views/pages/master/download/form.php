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
            <input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <div class="radio">
                            <label class="radio"><input type="radio" id="download_dealer" class="rpt_layout" name="opsi_master" value="1" checked onclick="handleRadioClick(this);"><?=lang("Dealers")?></label>
                            <label class="radio"><input type="radio" id="download_colour" class="rpt_layout" name="opsi_master" value="2" onclick="handleRadioClick(this);"><?=lang("Colours")?></label>
                            <label class="radio"><input type="radio" id="download_brand" class="rpt_layout" name="opsi_master" value="3" onclick="handleRadioClick(this);"><?=lang("Brands")?></label>
                            <label class="radio"><input type="radio" id="download_brandtype" class="rpt_layout" name="opsi_master" value="4" onclick="handleRadioClick(this);"><?=lang("Brand Types")?></label>								
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
<script type="text/javascript" info="init">

    function handleRadioClick(myRadio) {
        if (myRadio.value == "1"){

        }else{

        }
    }
</script>

<script type="text/javascript">
    $(function() {

        var master = {
            [SECURITY_NAME]:SECURITY_VALUE,
            "opsimaster": $('input[name=opsi_master]:checked').val(),
        };
        //var opsi_master = $('input[name=opsi_master]:checked').val();
        $("#btnDownload").click(function(event){
            event.preventDefault();
            insertData(master);
        });

        function insertData(master){
            var dataSubmit = $("#frmDownloadMaster").serializeArray();
            var detail = new Array();
            var opsi_master = $('input[name=opsi_master]:checked').val();
            opsimaster = master;
            $.each(opsimaster,function(i,v){
                detail.push(v);
            });

            dataSubmit.push({
                name:"detail",
                value: JSON.stringify(detail)
            });

            if (opsi_master == 1){
                url =  "<?= site_url() ?>master/downloadmaster/download_dealers/";
            }else if (opsi_master == 2){
                url =  "<?= site_url() ?>master/downloadmaster/download_colours/";
            }else if (opsi_master == 3){
                url =  "<?= site_url() ?>master/downloadmaster/download_brands/";
            }else if (opsi_master == 4){
                url =  "<?= site_url() ?>master/downloadmaster/download_brandtypes/"; 
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
                                        return;
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