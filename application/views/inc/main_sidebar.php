<?php
defined('BASEPATH') or exit('No direct script access allowed');
$cekAvatar = APPPATH . '../assets/app/users/avatar/avatar_' . $this->aauth->get_user_id() . '.jpg';
if (file_exists($cekAvatar)) {
	$avatar = base_url() . 'assets/app/users/avatar/avatar_' . $this->aauth->get_user_id() . '.jpg';
} else {
	$avatar = base_url() . 'assets/app/users/avatar/default.jpg';
}

?>

<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
	<!-- Sidebar user panel -->
	<div class="user-panel">
		<div class="pull-left image">
			<img src="<?= $avatar ?>" class="img-circle" alt="User Image">
		</div>
		<div class="pull-left info">
		</div>
		<div style="clear:both"></div>
	</div>
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu" data-widget="tree">
		<?= $this->menus->build_menu(); ?>
	</ul>
</section>
<!-- /.sidebar -->
<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>