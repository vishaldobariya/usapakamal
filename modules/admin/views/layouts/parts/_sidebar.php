<?php

use yii\helpers\Html;

?>
<div class="nk-sidebar">

	<div class="nk-nav-scroll-custom">
		<ul class="metismenu" id="menu">
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-view-dashboard"></i><span class="nav-text">DASHBOARD</span>', ['/admin/dashboard/index']) ?></li>
			<li class="nav-label">STORE</li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-view-dashboard"></i><span class="nav-text">PRODUCTS</span>', ['/market/product/index']) ?></li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-server"></i><span class="nav-text">CATEGORIES</span>', ['/market/category/index']) ?></li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-seal"></i><span class="nav-text">BRANDS</span>', ['/market/brand/index']) ?></li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-motorbike"></i><span class="nav-text">MAKES</span>', ['/market/make/index']) ?></li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-shield"></i><span class="nav-text">SUPPLIERS</span>', ['/market/supplier/index']) ?></li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-shopping"></i><span class="nav-text">ORDERS</span>', ['/market/order/index']) ?></li>
			<li class="nav-label">SYSTEM</li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-account-multiple"></i><span class="nav-text">USERS</span>', ['/user/user/index']) ?></li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-cogs"></i><span class="nav-text">SETTINGS</span>', ['/settings/view/index']) ?></li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-folder-multiple-image"></i><span class="nav-text">SLIDER</span>', ['/settings/slider/index']) ?></li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-comment-multiple-outline"></i><span class="nav-text">Testimonial</span>', ['/testimonial/view/index']) ?></li>
			<li class="mega-menu"><?= Html::a('<i class="mdi mdi-comment-multiple-outline"></i><span class="nav-text">Tags</span>', ['/market/tag/index']) ?></li>
		</ul>
	</div>
</div>