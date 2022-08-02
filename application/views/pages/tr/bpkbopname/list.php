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

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title"><?=$list_name?></h3>
				<div class="box-tools">
					<a id="btnNew" data-toggle="confirmation" href="" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New Opname</a>
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
<div id="MdlOpname" class="modal fade" role="dialog" data-backdrop="static">
	<div class="modal-dialog" style="width:100%">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title"><?= $title ?></h3>
			</div>
			<div class="modal-body">			
				<form id="frmBpkbOpname" class="form-horizontal" action="<?= site_url() ?>trx/bpkbopname/add" method="POST" enctype="multipart/form-data">
				<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
				<input type="hidden" id="frm-mode" value="">	
					<div class='form-group'>
                    	<label for="fstOpnameNo" class="col-sm-2 control-label">Opname No.</label>
						<div class="col-sm-10">
							<input type="TEXT" id="fstOpnameNo" name="fstOpnameNo" class="form-control" style="width:100%"  value="<?=$fstOpnameNo?>" placeholder="PREFIX/YEARMONTH/99999" readonly /> 
						</div>
					</div>
                    <div class='form-group'>
                    	<label for="fdtOpnameStartDate" class="col-sm-2 control-label">Start Date</label>
						<div class="col-sm-10">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control datetimepicker" style="width:100%" id="fdtOpnameStartDate" name="fdtOpnameStartDate"/>
							</div>
							<div id="fdtOpnameStartDate_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>
					</div>
					<div class="form-group">
						<label for="finWarehouseId" class="col-md-2 control-label"><?= lang("Warehouse") ?></label>
						<div class="col-md-4">
							<select class="form-control select2" id="finWarehouseId" name="finWarehouseId" style="width:100%">
							<?php
								$warehouseList = $this->msbpkbwarehouse_model->getAllList();
								foreach($warehouseList as $warehouse){
									echo "<option value='$warehouse->finWarehouseId'>$warehouse->fstWarehouseName</option>";
								}
							?>
							</select>
							<div id="finWarehouseId_err" class="text-danger"></div>
						</div>
						<div class="col-md-6" style='text-align:right'>
							<div class="btn-group btn-group-sm  pull-right">					
								<a id="btn-add-detail" class="btn btn-primary" href="#" title="<?=lang("Add BPKB")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
								<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>											
							</div>
						</div>
					</div>
					<table id="tblOpnameDetail" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
					<div class="form-group">
						<div class="col-sm-12">
							<textarea class="form-control" id="fstMemo" placeholder="<?= lang("Memo") ?>" name="fstMemo" rows="5" style="resize:none"></textarea>
							<div id="fstMemo_err" class="text-danger"></div>
						</div>
					</div>				
				</form>

			</div>
		</div>
	</div>
	<script type="text/javascript">
		var selected_bpkb;

		var mdlOpname = {
			show:function(data){
				mdlOpname.clear();
				//console.log(data);
				if (typeof(data) == "undefined"){
					$("#MdlOpname").modal("show");					
					return;
				}
				init_form(data.fstOpnameNo);

				$("#MdlOpname").modal({
					backdrop:"static",
				});
			},
			hide:function(){
				$("#MdlOpname").modal("hide");
			},
			clear:function(){
				$("#fstOpnameNo").val("<?=$fstOpnameNo?>");
				$("#fdtOpnameStartDate").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
				$('#finWarehouseId').val(null).trigger('change.select2');
				$("#fstMemo").val("")
				t = $("#tblOpnameDetail").DataTable();
				t.clear().draw();
			},
			checkin:function(){
				//selectedDetail								
				mdlOpname.clear();
			}			
		};

	</script>
</div>

<div id="mdlBpkb" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:100%">
        <!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add Opname Detail")?></h4>
			</div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
                                <form class="form-horizontal ">
                                    <input type='hidden' id='dfinRecId'/>
									<input type='hidden' id='dfstOpnameNo'/>
									<div class="form-group">
										<label for="dfstNotes" class="col-md-2 control-label"><?=lang("Note")?></label>
										<div class="col-md-10">
											<textarea type="text" class="form-control" id="dfstNotes" rows="2"></textarea>
										</div>
									</div>
                                    <div class="form-group">
										<label for="dfstBpkbNo" class="col-md-2 control-label"><?=lang("BPKB No")?></label>
										<div class="col-md-10">
											<input id="dfstBpkbNo" class="form-control"></select>
											<div id="dfstBpkbNo_err" class="text-danger"></div>
										</div>
									</div>

                                </form>
                                <div class="modal-footer" style="width:100%;padding:10px" class="text-center">
                                    <button id="btn-add-bpkb" type="button" class="btn btn-primary btn-sm text-center" style="width:15%" ><?=lang("Add")?></button>
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

        var mdlBpkb = {
			selectedDetail:null,
			show:function(){
                if (mdlBpkb.selectedDetail != null){
					var data = mdlBpkb.selectedDetail.data();
                    console.log(data);
                    if (typeof(data) == "undefined"){
                        $("#mdlBpkb").modal("show");
                        selectedDetail = null;					
                        return;
                    }
                    $('#dfstBpkbNo').prop('readonly', true);
					$("#dfinRecId").val(data.finRecId);
					$("#dfstOpnameNo").val(data.fstOpnameNo);
					$("#dfstBpkbNo").val(data.fstBpkbNo);
					$("#dfstNotes").val(data.fstNotes);							
                    $("#mdlBpkb").modal({
                        backdrop:"static",
                    });
                }else{
                    mdlBpkb.clear();
                    $('#dfstBpkbNo').prop('readonly', false);
                    $("#mdlBpkb").modal("show");
                    selectedDetail = null;					
                    return;
                }
            },

            hide:function(){
                $("#mdlBpkb").modal("hide");
            },

            clear:function(){
				$("#dfinRecId").val(0);
				$("#dfstOpnameNo").val(0);
                $("#dfstBpkbNo").val("");
                $("#dfstNotes").val("");
            },		
        };

        $(function() {

			$("#dfstBpkbNo").keydown(function(e){
				if (e.keyCode == 13){
					e.preventDefault();
					$("#btn-add-bpkb").trigger("click");
                    return;
				}
			});

            $("#btn-add-bpkb").click(function(event) {
                event.preventDefault();
				var mode="new";
                if (mdlBpkb.selectedDetail != null){
					mode= "update";
                }
                t = $('#tblOpnameDetail').DataTable();
                addRow = true;
                var bpkb = $("#dfstBpkbNo").val();
                var notes = $("#dfstNotes").val();
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
                        if (v.fstBpkbNo == bpkb && mode != "update") {
                            $("#dfstBpkbNo_err").html("BPKB No is already exist!");
                            $("#dfstBpkbNo_err").show();
                            addRow = false;
                            valid = false;
                            return false;
                        } else {
                            $("#dfstBpkbNo_err").hide();
                        }
                    });
                    if (valid == false){
                        return;
                    }
                }

				if(mode == "update"){
					t.row(mdlBpkb.selectedDetail).data({
						finRecId: 0,
						fstOpnameNo: 0,
						fblIsSystemInserted: 0,
						fstBpkbNo: bpkb,
						fstBpkbOpnameStatus:'WAITING_PROCESS',
						fstNotes: notes,
						action: action
					}).draw(false);
					mdlBpkb.hide();
				}else{
					t.row.add({
						finRecId: 0,
						fstOpnameNo: 0,
						fblIsSystemInserted: 0,
						fstBpkbNo: bpkb,
						fstBpkbOpnameStatus:'WAITING_PROCESS',
						fstNotes: notes,
						action: action
					}).draw(false);
					mdlBpkb.clear();
				}
            });
        });
    </script>
</div>

<script type="text/javascript">
	
	$(function(){

		$("#tblOpnameDetail").DataTable({
            searching: false,
            paging: false,
            info: false,
			columns:[
				{"title" : "id","width": "5%","data":"finRecId","sortable":false,visible:false},
				{"title" : "Opname No","width": "10%","data":"fstOpnameNo","sortable":true,visible:false},
				{"title" : "System Insert","width": "10%","data":"fblIsSystemInserted","sortable":true,visible:false},
				{"title" : "BPKB No","width": "10%","data":"fstBpkbNo","sortable":true},
                {"title" : "Notes","width": "10%","data":"fstNotes","sortable":true},
				{"title" : "Status","width": "10%","data":"fstBpkbOpnameStatus","sortable":true},

                {
                    "title": "Action",
                    "width": "5%",
                    render: function(data, type, row) {
						var action = "<a class='btn-edit' href='#'><i class='fa fa-pencil-square-o'></i></a>&nbsp;&nbsp;";
                        action += "<a class='btn-delete-opname-detail' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                        return action;
                    },
                    "sortable": false,
                    "className": "dt-body-center text-center"
                },                  				
			],
        }).on('draw',function(){
            $('.btn-delete-opname-detail').confirmation({
                title: "<?=lang('Delete record?')?>",
                rootSelector: '.btn-delete-opname-detail',
                // other options
            });	

		}).on('click','.btn-edit',function(e){
            e.preventDefault();
            t = $("#tblOpnameDetail").DataTable();
			var trRow = $(this).parents('tr');
			mdlBpkb.selectedDetail = t.row(trRow);						
			mdlBpkb.show();
        });

        $("#tblOpnameDetail").on("click", ".btn-delete-opname-detail", function(event) {
            event.preventDefault();
            t = $("#tblOpnameDetail").DataTable();
            var trRow = $(this).parents('tr');
            t.row(trRow).remove().draw();
        });
            
	});

</script>

<script type="text/javascript" info="DEFINE">
	var selectedOpname;		
</script>

<script type="text/javascript">
	var t;
	var trRow;
	var needConfirmDelete = false;
	var valid = false;

	$(function(){	
		
		if ($('#mdlEditForm').length > 0){
			needConfirmDelete = true;
		}

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

			$('.btn-close').confirmation({
				title: "<?=lang('Close Opname ?')?>",
				rootSelector: '.btn-close',
				// other options
			});	
		});
		
		$("#tblList").on("click",".btn-close",function(event){
            event.preventDefault();
			t = $('#tblList').DataTable();
			var trRow = $(this).parents('tr');
			selectedOpname = trRow;
			row = t.row(trRow).data();
			if (row.fstOpnameStatus == '' || row.fstOpnameStatus == null){
				closeOpname($(this));
			}else{
				alert("already Closed");
			}
            			
		});

		$("#tblList").on("click",".btn-opname",function(event){
			event.preventDefault();			
			t = $('#tblList').DataTable();
			var trRow = $(this).parents('tr');
			selectedOpname = trRow;
			row = t.row(trRow).data();
			if (row.fstOpnameStatus == '' || row.fstOpnameStatus == null){
				$("#frm-mode").val("EDIT");
				mdlOpname.show(row);
			}else{
				alert("already Closed");
			}	
		});

		$("#tblList").on("click",".btn-view",function(event){
			event.preventDefault();
			t = $('#tblList').DataTable();
			trRow = $(this).parents('tr');				
			row = t.row(trRow).data();
			id = row.<?=$pKey?>;
			valid = false;
			isvalid(id,row);				
		});

		$("#btnNew").click(function(e){
			e.preventDefault();
			$("#frm-mode").val("ADD");
			$('#btnSubmitAjax').show();
			$('#btn-add-detail').hide();
			$('#fdtOpnameStartDate').prop('disabled', false);
			$("#finWarehouseId").prop("disabled", false);
			mdlOpname.show();
			return;
		});

	});

	function isvalid(fstOpnameNo,row){
		var dataSubmit = [];
		dataSubmit.push({
			name : SECURITY_NAME,
			value: SECURITY_VALUE,
		});

		$.ajax({			
			url:"<?=$isvalid?>" + fstOpnameNo,
			method:"POST",
			data:dataSubmit,
			success:function(resp){
				if (resp.message != ""){
					alert(resp.message);
					valid = false;
				}

				if (resp.status == "READY"){
					valid = true;
					$("#frm-mode").val("VIEW");
					mdlOpname.show(row);
				}
			}
		})
	}

    function closeOpname(element){
        t = $('#tblList').DataTable();
        var trRow = element.parents('tr');
        data = t.row(trRow).data(); 
        dataPost = {
            <?=$this->security->get_csrf_token_name()?> : "<?=$this->security->get_csrf_hash()?>",
            fstOpnameNo: data.fstOpnameNo,
        };

        App.blockUIOnAjaxRequest("<?=lang("Please wait .....")?>");
        $.ajax({
            url:"<?= site_url() ?>trx/bpkbopname/closeOpname/",
            data:dataPost,
            method:"POST"

        }).done(function(resp){
            if (resp.message != "")	{
                $.alert({
                    title: 'Alert Message',
                    content: resp.message,
                    buttons : {
                        OK : function(){
                            if(resp.status == "SUCCESS"){
                                return;
                            }
                        },
                    }
                });
            }
			if(resp.status == "SUCCESS") {
				//window.location.replace("<?=site_url()?>trx/bpkbopname")
				$('#tblList').DataTable().ajax.reload();
				//return;
            }
        });
    }

	function deleteAjax(id,confirmDelete){
		var dataSubmit = [];
		dataSubmit.push({
			name : SECURITY_NAME,
			value: SECURITY_VALUE,
		});

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

<script type="text/javascript" info="EVENT">
	    $(function() {

			<?php if ($mode != "ADD") { ?>
				init_form($("#fstOpnameNo").val());
			<?php } ?>

			$("#btnSubmitAjax").click(function(event) {
				event.preventDefault();
				data = $("#frmBpkbOpname").serializeArray();
				detail = new Array();		
				t = $('#tblOpnameDetail').DataTable();
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
					url = "<?= site_url() ?>trx/bpkbopname/ajx_add_save";
				} else {
					url = "<?= site_url() ?>trx/bpkbopname/ajx_edit_save";
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
											window.location.replace("<?=site_url()?>trx/bpkbopname")
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
							$("#fstOpnameNo").val(data.insert_id);
							//Clear all previous error
							$(".text-danger").html("");
							// Change to Edit mode
							$("#frm-mode").val("EDIT"); //ADD|EDIT
							$('#fstOpnameNo').prop('readonly', true);

						}
					},
					error: function(e) {
						$("#result").text(e.responseText);
						console.log("ERROR : ", e);
						$("#btnSubmit").prop("disabled", false);
					}
				});
			});


			$("#btn-add-detail").click(function(event){
				event.preventDefault();
				mdlBpkb.selectedDetail = null;
				mdlBpkb.show();
			});
		});

		function init_form(fstOpnameNo) {
			var $userActive ="<?= $this->aauth->get_user_id()?>";
			var mode = $("#frm-mode").val();
			if (mode == "EDIT"){
				var url = "<?= site_url() ?>trx/bpkbopname/fetch_data/" +fstOpnameNo;
			}else{
				var url = "<?= site_url() ?>trx/bpkbopname/view_data/" +fstOpnameNo;
			}
			$.ajax({
				type: "GET",
				url: url,
				success: function(resp) {
					console.log(resp.opnameHeader);
					dataH = resp.opnameHeader;
					dataD = resp.opnameDetail;

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

					$("#fdtOpnameStartDate").val(dateTimeFormat(dataH.fdtOpnameStartDate)).datetimepicker("update");
					//$("#fdtOpnameStartDate").val(dateTimeFormat("<= date("Y-m-d H:i:s")?>")).datetimepicker("update");
					$('#finWarehouseId').val(dataH.finWarehouseId).trigger('change.select2');

					if (dataH.fin_insert_id != $userActive){
						$('#fdtOpnameStartDate').prop('disabled', true);
						$("#finWarehouseId").prop("disabled", true);
					}else{
						$('#fdtOpnameStartDate').prop('disabled', false);
						$("#finWarehouseId").prop("disabled", false);
					}

					if (mode == "EDIT" ){
						$('#btnSubmitAjax').show();
						$('#btn-add-detail').show();
					}else{
						$('#btnSubmitAjax').hide();
						$('#btn-add-detail').hide();
					}

					t = $('#tblOpnameDetail').DataTable();
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
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<!--
<script src="<?=base_url()?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net/js/datetime.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
-->
