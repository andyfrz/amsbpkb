<div class="modal fade" id="modal-import">
  <div class="modal-dialog">
	<div class="modal-content">
	<?php echo form_open_multipart('trx/salestrx/import',array('id' => 'frm-upload')); ?>
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<!--<h4 class="modal-title"><?=$modal_title; ?></h4>-->
	  </div>
	  <div class="modal-body">
			
			<div class="form-group">
				<label for="exampleInputFile">Import</label>
				<input type="file" id="exampleInputFile" name="file" required>

				<p class="margin">Format File .xls | .xlsx | maks size. 2 Mb</p>
			</div>
			<!--<div class="form-group">
				Untuk blanko import dosen download <a href="<?=base_url('assets/blanko/blanko_dosen.xlsx'); ?>" target="_blank">disini.</a>
			</div>-->
			
	  </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			<button type="submit" class="btn btn-primary">Import</button>
		</div>	 
		<?php echo form_close(); ?>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->