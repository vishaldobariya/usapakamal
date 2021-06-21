<?php

use yii\helpers\Html;
use yii\bootstrap4\Breadcrumbs;
use kartik\icons\FontAwesomeAsset;
use app\modules\admin\models\Theme;
use app\modules\admin\widgets\LeftMenu;
use app\modules\admin\assets\AdminAsset;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\widgets\SearchWidget;
use app\widgets\SubscribeWidget;

/**
 * @var Theme $theme
 */
$theme = Yii::$app->user->identity->theme;

AdminAsset::register($this);
FontAwesomeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $this->registerCsrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>

<body class="<?php echo (Yii::$app->user->identity->role != 'user')  ? 'admin' : 'admin-user' ?>" data-sidebar-position="<?= $theme->sidebar_position ?>" data-theme-version="<?= $theme->version ?>" data-header-position="<?= $theme->header_position ?>" data-sidebar-style="<?= $theme->sidebar_style ?>" data-layout="<?= $theme->layout ?>" data-container="<?= $theme->container_layout ?>" data-nav-headerbg="<?= $theme->navheader_bg ?>" data-headerbg="<?= $theme->header_bg ?>" data-sibebarbg="<?= $theme->sidebar_bg ?>">
	<?php $this->beginBody() ?>
	<div id="preloader">
		<div class="loader">
			<svg class="circular" viewBox="25 25 50 50">
				<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
			</svg>

		</div>
	</div>
	<header class="main-header js-header">
		<div class="header-top-bar py-1">
			<div class="container-fluid text-center">
				<?= Yii::$app->settings->user_message ?>
			</div>
		</div>
		<div class="header-panel">

			<div class="header-logo">
				<a href="/" class="logo">

					<img src="/images/logo-new.svg" alt="">
				</a>
			</div>

			<div class="header-left">
				<div class="header-top">
					<div class="header-search">
						<?= SearchWidget::widget() ?>
						<div class="header-nav-toggle">
							<img src="/images/icon-toggle.svg" alt="">
						</div>
					</div>

					<div class="header-actions">

						<ul class="header-links">
							<li class="header-links-item">
								<?php if (Yii::$app->user->isGuest) : ?>
									<a href="<?= Url::toRoute(['/sign/in']) ?>" class="header-links-link" data-toggle="tooltip" data-placement="top" data-original-title="My Account">
									<?php else : ?>
										<a href="<?= Url::toRoute(['/admin/dashboard/index']) ?>" class="header-links-link" data-toggle="tooltip" data-placement="top" data-original-title="My Account">
										<?php endif; ?>
										<div class="header-links-icon">
											<img src="/images/icon-login.png" alt="">
										</div>
										<div class="header-links-text hide-mobile">
											<?= Yii::$app->user->isGuest ?  'My Account' : 'Hi,' . Yii::$app->user->identity->first_name ?>
										</div>
										</a>

							</li>

							<li class="header-links-item">
								<a href="<?= Url::toRoute(['/site/contactus']) ?>" class="header-links-link" data-toggle="tooltip" data-placement="top" data-original-title=" Customer support">
									<div class="header-links-icon">
										<img src="/images/icon-customer-service.png" alt="">
									</div>
									<div class="header-links-text hide-mobile">
										Support
									</div>
								</a>

							</li>
							<li class="header-links-item header-links-item-cart">
								<a href="<?= Url::toRoute(['/shop/cart/cart']) ?>" class="header-links-link">
									<div class="header-links-icon">
										<img src="/images/icon/icon-cart.svg" alt="">
										<span class="header-links-qty js-count-cart"><?= Yii::$app->cart->count ?></span>
									</div>
									<div class="header-links-text pl-2">
										<span class=" js-cost-cart "><?= Yii::$app->formatter->asCurrency(Yii::$app->cart->cost) ?></span>
									</div>
								</a>

							</li>
							<li class="header-links-item header-links-ship">
								<a href="<?= Url::toRoute(['/sign/out']) ?>" class="header-links-link">
									<div class="header-links-icon">
									</div>
									<div class="header-links-text hide-mobile">
										Logout
									</div>
								</a>
							</li>
						</ul>



					</div>
					<div class="header-mobile">
						<div class="header-icon header-icon-search js-show-searchbar">
							<!-- <img src="/images/close-search.png" class="img-search-close"> -->

							<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="search" role="img" viewBox="0 0 512 512" class="img-search-open
							 svg-inline--fa fa-search fa-w-16 fa-3x">
								<path fill="#fff" d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" class=""></path>
							</svg>
						</div>
						<div class="header-links-item header-links-item-cart">
							<a href="<?= Url::toRoute(['/shop/cart/cart']) ?>" class="header-links-link">
								<div class="header-links-icon">
									<img src="/images/icon/icon-cart.svg" alt="">
									<span class="header-links-qty js-count-cart"><?= Yii::$app->cart->count ?></span>
								</div>
							</a>
						</div>

						<div class="header-nav-toggle">
							<img src="/images/icon-toggle.svg" alt="">
						</div>

					</div>

				</div>
				<div class="">
					<div class="header-nav">
						<div class="header-nav-inner">
							<div class="header-nav-close">
								<img src="/images/icon-close.svg" alt="">
							</div>
							<div class="header-nav-btns">
								<ul class="header-links">
									<li class="header-links-item">
										<?php if (Yii::$app->user->isGuest) : ?>
											<a href="<?= Url::toRoute(['/sign/in']) ?>" class="header-links-link" data-toggle="tooltip" data-placement="top" data-original-title="My Account">
											<?php else : ?>
												<a href="<?= Url::toRoute(['/admin/dashboard/index']) ?>" class="header-links-link" data-toggle="tooltip" data-placement="top" data-original-title="My Account">
												<?php endif; ?>
												<div class="header-links-icon">
													<img src="/images/icon-login.png" alt="">
												</div>
												<div class="header-links-text  ">
													My Account
												</div>
												</a>

									</li>

									<li class="header-links-item">
										<a href="<?= Url::toRoute(['/site/contactus']) ?>" class="header-links-link" data-toggle="tooltip" data-placement="top" data-original-title=" Customer support">
											<div class="header-links-icon">
												<img src="/images/icon-customer-service.png" alt="">
											</div>
											<div class="header-links-text ">
												Support
											</div>
										</a>

									</li>
									<li class="header-links-item header-links-item-cart">
										<a href="<?= Url::toRoute(['/shop/cart/cart']) ?>" class="header-links-link">
											<div class="header-links-icon">
												<img src="/images/icon/icon-cart.svg" alt="">
												<span class="header-links-qty js-count-cart"><?= Yii::$app->cart->count ?></span>
											</div>
											<div class="header-links-text pl-2">
												<span class=" js-cost-cart "><?= Yii::$app->formatter->asCurrency(Yii::$app->cart->cost) ?></span>
											</div>
										</a>

									</li>

								</ul>

							</div>

							<ul class="nav">
								<li class="nav-item has-submenu">
									<a href="<?= Url::toRoute(['/shop/shop/collections']) ?>" class="nav-item-link">All
										Alcohol</a>
									<div class="submenu">
										<h3>All Alcohol</h3>
									</div>
								</li>
								<li class="nav-item has-submenu">
									<a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[category_id]' => [2256]]) ?>" class="nav-item-link">WHISKEY</a>
									<div class="submenu">
										<h3>WHISKEY</h3>
									</div>
								</li>
								<li class="nav-item has-submenu">
									<a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[category_id]' => [2273]]) ?>" class="nav-item-link">TEQUILA</a>
									<div class="submenu">
										<h3>TEQUILA</h3>
									</div>
								</li>
								<li class="nav-item has-submenu">
									<a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[category_id]' => [2266]]) ?>" class="nav-item-link">VODKA</a>
									<div class="submenu">
										<h3>VODKA</h3>
									</div>
								</li>
								<li class="nav-item has-submenu">
									<a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[category_id]' => [2284]]) ?>" class="nav-item-link">GIN</a>
									<div class="submenu">
										<h3>GIN</h3>
									</div>
								</li>
								<li class="nav-item has-submenu">
									<a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[category_id]' => [2255]]) ?>" class="nav-item-link">RUM</a>
									<div class="submenu">
										<h3>RUM</h3>
									</div>
								</li>
								<li class="nav-item has-submenu">
									<a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[category_id]' => [2258]]) ?>" class="nav-item-link">COGNAC</a>
									<div class="submenu">
										<h3>COGNAC</h3>
									</div>
								</li>
								<li class="nav-item has-submenu">
									<a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[category_id]' => [2264]]) ?>" class="nav-item-link">LIQUEUR</a>
									<div class="submenu">
										<h3>LIQUEUR</h3>
									</div>
								</li>
								<li class="nav-item has-submenu">
									<a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[category_id]' => [2262]]) ?>" class="nav-item-link">WINE</a>
									<div class="submenu">
										<h3>WINE</h3>
									</div>
								</li>

								<li class="nav-item hide-mobile">
									<a href="#" class="nav-item-link nav-item-link--search js-show-search">
										<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="search" role="img" viewBox="0 0 512 512" class="svg-inline--fa fa-search fa-w-16 fa-3x">
											<path fill="#fff" d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" class="" />
										</svg>
									</a>

								</li>
								<li class="nav-item hide-mobile">

									<a href="<?= Url::toRoute(['/shop/cart/cart']) ?>" class="nav-item-link nav-item-link--cart">
										<div class="header-links-icon">
											<img src="/images/icon/icon-cart.svg" alt="">
											<span class="header-links-qty  header-links-qty-fix js-count-cart"><?= Yii::$app->cart->count ?></span>

										</div>

									</a>
								</li>
							</ul>
							<div class="header-btn-icon header-cart-icon">
								<a href="<?= Url::toRoute(['/shop/cart/cart']) ?>">
									<img src="/images/icon-cart.svg" alt="">
									<span class="header-cart-qwt js-count-cart"><?= Yii::$app->cart->count . ' items - ' . Yii::$app->formatter->asCurrency(Yii::$app->cart->cost) ?></span></a>
							</div>

						</div>
					</div>
				</div>
			</div>



		</div>

	</header>
	<div id="main-wrapper" class="page  show  ">

		<?php if (Yii::$app->user->identity->role != 'user') : ?>
			<?= $this->render('parts/_header') ?>
			<?= LeftMenu::widget() ?>
		<?php endif; ?>
		<div class="content-body">
			<div class="container-fluid">
				<?php if ($this->context->id !== 'dashboard') : ?>
					<div class="row page-titles">
						<div class="col p-md-0">
							<h4><?= $this->title ?? '' ?></h4>
						</div>
						<div class="col p-md-0">
							<?= Breadcrumbs::widget([
								'links'    => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
								'homeLink' => ['label' => 'Dashboard', 'url' => ['/admin/dashboard/index']],
							]) ?>
						</div>
					</div>
				<?php endif ?>
				<?= $content ?>

			</div>


		</div>
		<?//= $this->render('parts/_footer') ?>
		<?= $this->render('parts/_right_sidebar') ?>
		<button id="to-top" class="to-top">
			<i class="fas fa-chevron-up"></i>
		</button>
	</div>


	<?= SubscribeWidget::widget() ?>


	<footer class="footer   padding-bottom-sm">
		<div class="container">
			<div class=" ">
				<div class="footer-nav-wrap">
					<ul class="footer-nav">
						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/contactus']) ?>" class="footer-nav-link">
								About us
							</a>
						</li>
						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/terms']) ?>" class="footer-nav-link">
								Terms of Service
							</a>
						</li>
						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/privacy']) ?>" class="footer-nav-link">
								Privacy Policy
							</a>
						</li>
						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/delivery']) ?>" class="footer-nav-link">
								DELIVERY
							</a>
						</li>
						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/contactus']) ?>" class="footer-nav-link">
								PARTNERSHIPS
							</a>
						</li>
						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/contactus']) ?>" class="footer-nav-link">
								Contact us
							</a>
						</li>


					</ul>
					<ul class="footer-nav">

						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/contactus']) ?>" class="footer-nav-link">
								FAQ
							</a>
						</li>
						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/contactus']) ?>" class="footer-nav-link">
								CUSTOMER SERVICE
							</a>
						</li>
						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/contactus']) ?>" class="footer-nav-link">
								ORDER STATUS
							</a>
						</li>
						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/policy']) ?>" class="footer-nav-link">
								RETURN POLICY
							</a>
						</li>
						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/contactus']) ?>" class="footer-nav-link">
								SUBMIT A PRODUCT
							</a>
						</li>

						<li class="footer-nav-item">
							<a href="<?= Url::toRoute(['/site/contactus']) ?>" class="footer-nav-link">
								E-COMMERCE FOR BRANDS
							</a>
						</li>

					</ul>
				</div>


				<div class="col-md-12">
					<p class=" text-center mt-3">
						<a href="/" class="footer-logo text-white">
							RoyalBatch
						</a>
					</p>
					<p class="footer-info">
						By using this site you acknowledge you are at least 21 years old and you afree to our Terms
						& Conditions.
					</p>
					<p class="footer-info">
						© <?php echo date('Y'); ?> RoyalBatch.com - All Rights Reserved. - All orders and sales are processed and
						fulfilled through local licensed retailers.
					</p>
					<p class="footer-info">
						Prop 65 warning for CA residents: WARNING: Drinking Distilled Spirits, Beer, Coolers, Wine
						and Other Alcoholic Beverages May Increase Cancer Risk, and, During Pregnancy, Can Cause
						Birth Defects.
					</p>
				</div>
			</div>
		</div>
	</footer>
	<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>