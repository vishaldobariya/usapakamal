<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>
<div class="nav-header">
	<div class="brand-logo"><a class="logo" href="<?= Url::toRoute(['/site/index']) ?>"><span class="brand-title"><?= Yii::$app->name ?></span></a></div>
	<div class="nav-control">
		<div class="hamburger"><span class="line"></span> <span class="line"></span> <span class="line"></span>
		</div>
	</div>
</div>
<div class="header">
	<div class="header-content">
		<div class="header-left">
			<ul>
				<li class="icons position-relative"><a href="javascript:void(0)"><i class="icon-magnifier f-s-16"></i></a>
					<div class="drop-down animated bounceInDown">
						<div class="dropdown-content-body">
							<div class="header-search" id="header-search">
								<form action="#">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Search">
										<div class="input-group-append"><span class="input-group-text"><i class="icon-magnifier"></i></span>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="header-right">
			<ul class="d-flex align-items-center">
				<li class="icons">
					<a href="javascript:void(0)" class="log-user"> <span><?= Yii::$app->user->identity->name ?></span> <i class="fa fa-caret-down f-s-14" aria-hidden="true"></i> </a>
					<div class="drop-down dropdown-profile animated bounceInDown">
						<div class="dropdown-content-body">
							<ul>
								<?php if(!Yii::$app->user->identity->role != 'distributor') : ?>
								<li><a href="<?= Url::toRoute(['/admin/dashboard/user-profile']) ?>"><i class="icon-user"></i> <span>My Profile</span></a>
									<?php else: ?>
								<li><a href="<?= Url::toRoute(['/admin/dashboard/profile']) ?>"><i class="icon-user"></i> <span>My Profile</span></a>
									<?php endif; ?>
								<li><?= Html::a('Logout', ['/sign/out'], ['class' => 'nav__item']) ?>
								</li>
							</ul>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>
