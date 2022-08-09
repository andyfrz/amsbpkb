<div class="modal fade in" id="modal-import">
  <div class="modal-dialog">
	<div class="modal-content">
    <?php echo form_open_multipart('trx/salestrx/import',array('id' => 'frm-upload')); ?>
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
	  </div>
	  <div class="modal-body">
			<div class="form-group">
				<label for="exampleInputFile">Import</label>
				<input type="file" id="exampleInputFile" name="file" required>
				<p class="margin">Format File .xls | .xlsx | maks size. 10 Mb</p>
			</div>
	  </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			<button type="submit" class="btn btn-primary">Import</button>
		</div>
        <?php
        if (isset($dataInfo)) {
        ?>
            <table class="table">
                <thead class="bg-gray">
                    <tr>
                        <th>REGIST</th>
                        <th>CUSTOMER</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($dataInfo as $data) {
                    ?>
                        <tr>
                            <td><?= $data['REGIST']; ?></td>
                            <td><?= $data['CUSTOMER']; ?></td>
                            <td><?= $data['info']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        }
            ?>
		<?php echo form_close(); ?>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->