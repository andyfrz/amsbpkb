<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net/datatables.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="MdlPrincipalInvoice" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:500px">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= lang("Sales Trx") ?></h4>
			</div>
			<div class="modal-body">
                <input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">				
				<form class="form-horizontal">
			
				</form>

                <table id="tblSalesTrx" style="width:100%"></table>
                
			</div>
		</div>
	</div>
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
		
		});
            
        
        $("#btnShowData").click(function(e){
            e.preventDefault();
            var dataPost = {
                [SECURITY_NAME]:SECURITY_VALUE,
                "fstCustomerName": $("#fstCustomerName").val(),
                "fstNik":$("#fstNik").val(),
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

        $("#tblSalesTrx").on("click",".btn-add-trx",function(event){
			/*id = $(this).data("<?=$trx_key?>");
			if (typeof id === "undefined") {
				t = $('#tblList').DataTable();
				var trRow = $(this).parents('tr');				
				data = t.row(trRow).data();
				id = data.finSalesTrxId;
			}*/
            id = data.finSalesTrxId;
            alert(id);
		});
	});

</script>

<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>