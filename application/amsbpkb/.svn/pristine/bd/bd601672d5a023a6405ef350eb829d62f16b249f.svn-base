<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<section class="content-header">
    <h1><?=lang("History")?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Menus") ?></a></li>
		<li class="active title"><?= $title ?></li>
    </ol>
</section>

<section class="content">

<!-- Main row -->
<div class="row">
<!-- Left col -->
<div class="col-md-12">
    <div class="row">
    <div class="col-md-12">
        <!-- DOWNLOAD LIST -->
        <div class="box box-info">
        <div class="box-header with-border">
              <h3 class="box-title">History download data</h3>
              <h3 class="box-title pull-right"><?= $branch ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table no-margin">
                <thead>
                <tr>
                <th>Branch Code</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Datarange Startdate</th>
                <th>Datarange Enddate</th>
                <th>Status</th>
                <th>Info</th>
                </tr>
                </thead>
                <tbody id="tbldownload">
                <?php foreach($downloads as $download):
                $status = $download['fst_status'];

                ?>
                    <tr>
                    <td><?php echo $download['fst_branch_code']; ?></td>
                    <td>
                    <div class="sparkbar" data-color="#00a65a" data-height="20"><?= date("d-M-Y",strtotime($download["fdt_start_datetime"])) ?></div>
                    </td>
                    <td>
                    <div class="sparkbar" data-color="#00a65a" data-height="20"><?= date("d-M-Y",strtotime($download["fdt_end_datetime"])) ?></div>
                    </td>
                    <td>
                    <div class="sparkbar" data-color="#00a65a" data-height="20"><?= date("d-M-Y",strtotime($download["fdt_datarange_start_date"])) ?></div>
                    </td>
                    <td>
                    <div class="sparkbar" data-color="#00a65a" data-height="20"><?= date("d-M-Y",strtotime($download["fdt_datarange_end_date"])) ?></div>
                    </td>
                    <?php
                    if ( $status  == 'SUCCESS'){
                        echo "<td><span class='label label-info'>$status</span></td>";
                    }else if ( $status  == 'FAILED'){
                        echo "<td><span class='label label-danger'>$status</span></td>";
                    }else{
                        echo "<td><span class='label label-warning'>$status</span></td>";
                    }
                    ?>
                    <td><?php echo $download['fst_info']; ?></td>
                    <td>

                    </tr>
                <?php endforeach; ?>

                </tbody>
                </table>
            </div>
        </div>
        <!-- /.box-body -->
        </div>
        <!--/.direct-chat -->
    </div>
    <!-- /.col -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
