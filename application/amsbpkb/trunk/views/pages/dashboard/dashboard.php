<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<section class="content-header">
    <h1><?=lang("Dashboard")?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Menus") ?></a></li>
		<li class="active title"><?= $title ?></li>
    </ol>
</section>

<section class="content">
<!-- Info boxes -->
<div class="row">
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
    <span class="info-box-icon bg-green"><i class="ion-archive"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Total Download</span>
        <span class="info-box-number">1</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
    <span class="info-box-icon bg-aqua"><i class="ion-checkmark-circled"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Success</span>
        <span class="info-box-number">2</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->

<!-- fix for small devices only -->
<div class="clearfix visible-sm-block"></div>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
    <span class="info-box-icon bg-yellow"><i class="ion ion-load-b"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">On Progress</span>
        <span class="info-box-number">3</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
    <span class="info-box-icon bg-red"><i class="ion-alert-circled"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Failed</span>
        <span class="info-box-number">4</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
