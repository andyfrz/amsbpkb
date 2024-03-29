<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- <link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> -->
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net/datatables.min.css">

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
				<table id="tblList" class="table table-bordered table-hover table-striped row-border compact nowarp" style="min-width:100%"></table>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
			</div>
			<!-- /.box-footer -->		
		</div>
	</div>
</div>
<div id="MdlPrincipalInvoice" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:1300px">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= lang("Sales Trx") ?></h4>
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

                <table id="tblSalesTrx" style="width:100%"></table>
                
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var selected_items;

		var mdlInvoice = {
			show:function(data){
				mdlInvoice.clear();
				console.log(data);
				if (typeof(data) == "undefined"){
					$("#MdlPrincipalInvoice").modal("show");					
					return;
				}								
				$("#MdlPrincipalInvoice").modal({
					backdrop:"static",
				});
			},
			hide:function(){
				$("#MdlPrincipalInvoice").modal("hide");
			},
			clear:function(){
				$("#fstCustomerName").val("");
				$("#fstNik").val("");
				$("#fstSPKNo").val("");
				$("#fstBrandName").val("");
				$("#fstEngineNo").val("");
				$("#fstChasisNo").val("");
				t = $("#tblSalesTrx").DataTable();
				t.clear().draw();
			},
			checkin:function(){
				//selectedDetail								
				mdlBpkb.clear();
			}			
		};

	</script>
</div>

<script type="text/javascript">
	
	$(function(){

		$('#tblSalesTrx').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
			 //data.sessionId = "TEST SESSION ID";
			 data.optionSearch = $('#selectSearch').val();
			 data.dateLog = $("#date-log").val();
		}).DataTable({
			columns:[
				{"title" : "id","width": "5%","data":"finSalesTrxId","sortable":false,visible:false},
				{"title" : "Dealer","width": "10%","data":"fstDealerCode","sortable":true},
				{"title" : "SPK No","width": "10%","data":"fstSPKNo","sortable":true},
				{"title" : "Sales Date","width": "10%","data":"fdtSalesDate","sortable":true},
                {"title" : "NIK","width": "10%","data":"fstNik","sortable":true},
                {"title" : "Customer","width": "10%","data":"fstCustomerName","sortable":true},
                {
                    "title": "Action",
                    "width": "5%",
                    render: function(data, type, row) {
                        action = "<a class='btn-add-trx' href='#'><i class='fa fa-plus-square'></i></a>&nbsp;";
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
				title: "<?=lang('Proses Checkin / Claim ?')?>",
				rootSelector: '.btn-add-trx',
				// other options
			});	
		
        })

        $("#tblSalesTrx").on("click",".btn-add-trx",function(e){
			e.preventDefault();
			t = $("#tblSalesTrx").DataTable();
			var trRow = $(this).parents('tr');
			selectedDetail  = t.row(trRow);
			var data = t.row(trRow).data();
			//mdlAddItems.show(data);
            //alert(data.finSalesTrxId);
            checkinAjax(data.finSalesTrxId);		
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

            var t = $('#tblSalesTrx').DataTable();
            blockUIOnAjaxRequest();
            $.ajax({
                url:"<?=site_url()?>trx/principalinvoices/ajxSalesTrxData",
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
                            fstCustomerName:record.fstCustomerName
                        };
                        t.row.add(dataRow);
                    });
                    t.draw(false);
                }
            });
        });
	});

    function checkinAjax(id){
		var dataSubmit = [];
		dataSubmit.push({
			name : SECURITY_NAME,
			value: SECURITY_VALUE,
		});

		$.ajax({			
			url:"<?=$checkin_ajax_url?>" + id,
			method:"POST",
			data:dataSubmit,
			success:function(resp){
				if (resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){
					//t.row(trRow).remove().draw(false); //refresh ajax
					trRow.remove(); //no refresh ajax
					$('#tblList').DataTable().ajax.reload();
				}
			}
		})
	}

</script>
<?php
	if(isset($jsfile)){
		echo $jsfile;
	}
?>
<script type="text/javascript">
	var t;
	var trRow;
	var needConfirmDelete = false;

	$(function(){	
		
		/*if ($('#mdlSalesTrx').length > 0){
			needConfirmDelete = true;
		}*/
        

		$('#tblList').on('preXhr.dt', function ( e, settings, data ) {
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
		
		$("#tblList").on("click",".btn-delete",function(event){
			t = $('#tblList').DataTable();
			trRow = $(this).parents('tr');				
			data = t.row(trRow).data();
			id = data.<?=$pKey?>;
			
			//

			deleteAjax(id,false);			
		});

		$("#btnNew").click(function(e){
			e.preventDefault();
			mdlInvoice.show();
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
