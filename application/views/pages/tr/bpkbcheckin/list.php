<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- <link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> -->
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net/datatables.min.css">
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
	<h1><?=$page_name?><small>List</small></h1>
	<ol class="breadcrumb">
		<?php 
			foreach($breadcrumbs as $breadcrumb){
				if ($breadcrumb["link"] == NULL){
					echo "<li class='active'>".$breadcrumb["title"]."</li>";
				}else{
					echo "<li><a href='".$breadcrumb["link"]."'>".$breadcrumb["icon"].$breadcrumb["title"]."</a></li>";
				}
			} 
		?>
	</ol>
</section>

<section class="content" style="font-size: 10px">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title"><?=$list_name?></h3>
				<div class="box-tools">
					<a id="btnNew" data-toggle="confirmation" href="#" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New Record</a>
					<?php if (isset($report_url)){ ?>
						<a id="btnPrint" href="<?=$report_url?>" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Report </a>
					<?php } ?>
				</div>
			</div>			
			<!-- /.box-header -->
			<div class="box-body">
				<div align="right">
					<span>Search on:</span>
					<span>
                        <select id="selectSearch" name="selectSearch" style="width: 148px;background-color:#e6e6ff;padding:8px;margin-left:6px;margin-bottom:6px">                            
                            <?php
                                foreach($arrSearch as $key => $value){ ?>
                                    <option value=<?=$key?>><?=$value?></option>
                                <?php
                                }
							// <option value="a.fin_id">No.Transaksi</option>
							// <option value="a.fst_customer_name">Customer</option>
                            // <option value="c.fst_salesname">Sales Name</option>
                            ?>
						</select>
					</span>
				</div>
				<table id="dtblList" class="table table-bordered table-hover table-striped row-border compact nowarp" style="min-width:100%"></table>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
			</div>
			<!-- /.box-footer -->		
		</div>
	</div>
</div>
<div id="MdlBpkb" class="modal fade" role="dialog">
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
                        <label for="fstSPKNo" class="col-sm-1 control-label">SPK No.</label>
						<div class="col-sm-2">
                            <input type="text" id="fstSPKNo" class="form-control"></input>
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
                <table id="dtblSalesTrx" style="width:100%"></table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var selected_items;

		var mdlBpkb = {
			show:function(data){
				mdlBpkb.clear();
				console.log(data);

				if (typeof(data) == "undefined"){
					$("#MdlBpkb").modal("show");					
					return;
				}								
				$("#MdlBpkb").modal({
					backdrop:"static",
				});
			},
			hide:function(){
				$("#MdlBpkb").modal("hide");
			},
			clear:function(){
				$("#fstCustomerName").val("");
				$("#fstNik").val("");
				$("#fstSPKNo").val("");
				$("#fstBrandName").val("");
				$("#fstEngineNo").val("");
				$("#fstChasisNo").val("");
				t = $("#dtblSalesTrx").DataTable();
				t.clear().draw();
			},
			checkin:function(){
				//selectedDetail								
				mdlBpkb.clear();
			}			
		};

	</script>
</div>

<?php echo $mdlPrint ?>
<?php
	if(isset($jsfile)){
		echo $jsfile;
	}
?>

<script type="text/javascript">
	
	$(function(){

		$('#dtblSalesTrx').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
			 //data.sessionId = "TEST SESSION ID";
			 data.optionSearch = $('#selectSearch').val();
			 //data.dateLog = $("#date-log").val();
		}).DataTable({
			columns:[
				{"title" : "id","width": "5%","data":"finSalesTrxId","sortable":false,visible:false},
				{"title" : "Dealer","width": "10%","data":"fstDealerCode","sortable":true},
				{"title" : "SPK No","width": "10%","data":"fstSPKNo","sortable":true},
				{"title" : "Sales Date","width": "10%","data":"fdtSalesDate","sortable":true},
                {"title" : "NIK","width": "10%","data":"fstNik","sortable":true},
                {"title" : "Customer","width": "10%","data":"fstCustomerName","sortable":true},
                {"title" : "Engine No.","width": "10%","data":"fstEngineNo","sortable":true},
                {"title" : "Chasis No.","width": "10%","data":"fstChasisNo","sortable":true},
                {
                    "title": "Action",
                    "width": "5%",
                    render: function(data, type, row) {
                        action = "<a class='btn-add-trx' href='#'><i class='fa fa-check-square'></i></a>&nbsp;";
                        return action;
                    },
                    "sortable": false,
                    "className": "dt-body-center text-center"
                },                  				
			],			
			processing: false,
			serverSide: false,
		}).on('draw',function(){

            $('.btn-add-trx').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				title: "<?=lang('Proses data ini ?')?>",
				rootSelector: '.btn-add-trx',
				// other options
			});	
		
        })

        $("#dtblSalesTrx").on("click",".btn-add-trx",function(e){
			e.preventDefault();
			t = $("#dtblSalesTrx").DataTable();
			var trRow = $(this).parents('tr');
			selectedDetail  = t.row(trRow);
			var data = t.row(trRow).data();
			//mdlAddItems.show(data);
            //alert(data.finSalesTrxId);
            ischeckin(data.finSalesTrxId);		
		});
            
        
        $("#btnShowData").click(function(e){
            e.preventDefault();
            var dataPost = {
                [SECURITY_NAME]:SECURITY_VALUE,
                "fstCustomerName": $("#fstCustomerName").val(),
                "fstNik":$("#fstNik").val(),
                "fstSPKNo":$("#fstSPKNo").val(),
                "fstBrandName":$("#fstBrandName").val(),
                "fstEngineNo":$("#fstEngineNo").val(),
                "fstChasisNo":$("#fstChasisNo").val(),
            };

            var t = $('#dtblSalesTrx').DataTable();
            blockUIOnAjaxRequest();
            $.ajax({
                url:"<?=site_url()?>trx/bpkbcheckin/ajxSalesTrxData",
                method:"POST",
                data:dataPost,
            }).done(function(resp){
                if (resp.status == "SUCCESS"){
                    t.clear();
                    records = resp.data;
                    $.each(records, function(i,record){
                        var dataRow = {
                            finSalesTrxId:record.finSalesTrxId,
                            fstDealerCode:record.fstDealerCode,
                            fstSPKNo:record.fstSPKNo,
							fdtSalesDate:record.fdtSalesDate,
                            fstNik:record.fstNik,
                            fstCustomerName:record.fstCustomerName,
                            fstEngineNo:record.fstEngineNo,
                            fstChasisNo:record.fstChasisNo
                        };
                        t.row.add(dataRow);
                    });
                    t.draw(false);
                }
            });
        });
	});

    function ischeckin(id){
		var dataSubmit = [];
		dataSubmit.push({
			name : SECURITY_NAME,
			value: SECURITY_VALUE,
		});

		$.ajax({			
			url:"<?=$ischeckin?>" + id,
			method:"POST",
			data:dataSubmit,
			success:function(resp){
				if (resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "NOT READY"){
					//t.row(trRow).remove().draw(false); //refresh ajax
					//trRow.remove(); //no refresh ajax
                    //$("#MdlCheckin").modal("show");
					mdlCheckin.show(id);
				}
			}
		})
	}

</script>

<div id="MdlCheckin" class="modal fade in" role="dialog" style="display: none">
	<div class="modal-dialog" style="display:table;width:650px">
		<!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= lang("BPKB Checkin") ?></h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:1px">
                            <fieldset style="padding:10px">
                                <form class="form-horizontal ">
									<input type='hidden' id='finSalesTrxId'/>
                                    <div class="form-group">
										<label for="cfstBpkbNo" class="col-md-3 control-label"><?=lang("BPKB No")?></label>
										<div class="col-md-9">
											<input id="cfstBpkbNo" class="form-control"></input>
											<div id="cfstBpkbNo_err" class="text-danger"></div>
										</div>
									</div>
									<div class="form-group">
									<label for="cfdtBpkbDate" class="col-md-3 control-label"><?= lang("BPKB Date") ?></label>
										<div class="col-md-9">
											<div class="input-group date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control datepicker" id="cfdtBpkbDate" name="cfdtBpkbDate"/>
											</div>
											<div id="cfdtBpkbDate_err" class="text-danger"></div>
											<!-- /.input group -->
										</div>
									</div>
                                    <div class="form-group">
										<label for="cfstInfo" class="col-md-3 control-label"><?=lang("Note")?></label>
										<div class="col-md-9">
											<textarea type="text" class="form-control" id="cfstInfo" rows="3"></textarea>
										</div>
									</div>
                                </form>
								<div class="modal-footer">
									<button id="btnCheckin" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Checkin")?></button>
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
		var selected_items;

		var mdlCheckin = {
			show:function(id){
				mdlCheckin.clear();
				console.log(id);
				//alert(id);

				if (typeof(id) == "undefined"){
					$("#MdlCheckin").modal("show");				
					return;
				}
				$("#finSalesTrxId").val(id);
				$("#cfstBpkbNo").val("");			
				$("#cfdtBpkbDate").val("");
				$('#cfstInfo').val("");								
				$("#MdlCheckin").modal({
					backdrop:"static",
				});
				

			},
			hide:function(){
				$("#MdlCheckin").modal("hide");
			},
			clear:function(){
				$("#finSalesTrxId").val(0);
				$("#cfstBpkbNo").val("");
				$("#cfdtBpkbDate").val("");
				$("#cfstInfo").val("");
			},
			checkin:function(){								
				mdlCheckin.clear();
			}			
		};

		$(function(){
			$("#btnCheckin").click(function(event){
				event.preventDefault();
				var dataPost = {
					[SECURITY_NAME]:SECURITY_VALUE,
					"finSalesTrxId": $("#finSalesTrxId").val(),
					"fstBpkbNo":$("#cfstBpkbNo").val(),
					"fdtBpkbDate":$("#cfdtBpkbDate").val(),
					"fstInfo":$("#cfstInfo").val(),
				};
				$.ajax({			
					url:"<?=$checkin_ajax_url?>",
					method:"POST",
					data:dataPost,
					success:function(resp){
						if (resp.message != "")	{
							$.alert({
								title: 'Message',
								content: resp.message,
								buttons : {
									OK : function(){
										if(resp.status == "SUCCESS"){
											mdlCheckin.clear();
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
							$("#finSalesTrxId").val(data.insert_id);
							//Clear all previous error
							$(".text-danger").html("");					
						}
					},
					error: function (e) {
						$("#result").text(e.responseText);
						$("#btnSubmit").prop("disabled", false);
					},
				}).always(function(){	

				});				
			});
		});

	</script>
</div>

<script type="text/javascript">
	var t;
	var trRow;
	var needConfirmDelete = false;

	$(function(){	

		$('#dtblList').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
			 //data.sessionId = "TEST SESSION ID";
			 data.optionSearch = $('#selectSearch').val();
		}).DataTable({
			scrollX: true,
			scrollCollapse: true,
			order:[[0,"desc"]],
			columns:[
                <?php
					foreach($columns as $col){?>
						<?php
							$strData = isset($col['data']) ? ",data:\"" . $col['data'] ."\"" : "";

						?>
						{"title" : "<?=$col['title']?>","width": "<?=$col['width']?>"
							<?= $strData ?>
                            <?php if(isset($col['render'])){?>
                                ,"render":<?php echo $col['render'] ?>
                            <?php } ?>
							<?php if(isset($col['visible'])){?>
                                ,"visible":<?php echo $col['visible'] ?>
                            <?php } ?>
                            <?php if(isset($col['sortable'])){
                                if ($col['sortable']){ ?>
                                    ,"sortable": true
                                <?php }else
                                {?>
                                    ,"sortable": false
                                <?php }
                                
                            } ?>
                            <?php if(isset($col['className'])){?>
                                ,"className":"<?=$col['className']?>"
                            <?php } ?>
                        },
                    <?php }
                ?>
			],
			dataSrc:"data",
			processing: true,
			serverSide: true,
			ajax: "<?=$fetch_list_data_ajax_url?>"
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");

			$('.btn-delete').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				title: "<?=lang('Delete data ?')?>",
				rootSelector: '.btn-delete',
				// other options
			});	
		});
		
		$("#dtblList").on("click",".btn-delete",function(event){
			t = $('#dtblList').DataTable();
			trRow = $(this).parents('tr');				
			data = t.row(trRow).data();
			id = data.<?=$pKey?>;
			//
			deleteAjax(id,false);			
		});

        $("#btnNew").click(function(e){
			e.preventDefault();
			mdlBpkb.show();
			return;
		});

	});

	function deleteAjax(id,confirmDelete){
		var dataSubmit = [];
		dataSubmit.push({
			name : SECURITY_NAME,
			value: SECURITY_VALUE,
		});
		
		if (needConfirmDelete){
			if (confirmDelete == 0){
				MdlEditForm.saveCallBack = function(){
					deleteAjax(id,1);
				};		
				MdlEditForm.show();
				return;
			}			
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});
		}

		$.ajax({			
			url:"<?=$delete_ajax_url?>" + id,
			method:"POST",
			data:dataSubmit,
			success:function(resp){
				if (resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){
					//t.row(trRow).remove().draw(false); //refresh ajax
					trRow.remove(); //no refresh ajax
				}
			}
		})
	}
</script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<!--
<script src="<?=base_url()?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net/js/datetime.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
-->
