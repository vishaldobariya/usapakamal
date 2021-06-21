<?php

/* @var $this View */

/* @var $content string */

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\AppAsset;
use app\widgets\SearchWidget;
use app\widgets\SubscribeWidget;

AppAsset::register($this);
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
    <link href="<?= Url::canonical() ?>" rel="canonical">
    <link rel="icon" type="image/png" href="/images/favicon.png">
    <link rel="shortcut icon" href="/images/favicon.png">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
    <style>
        .no-verify {
            position: fixed;
            overflow: hidden;
        }

        .no-verify main {
            opacity: 0;
        }


        .greet {
            visibility: hidden;
            opacity: 0;
            /* transition: opacity 0.5s ease; */
            position: fixed;
            display: none;
        }

        .no-verify .greet {

            opacity: 1;
            visibility: visible;
            background-color: #fff;
            z-index: 9999;
            /* background-image: url(/images/hero.jpg); */
            background-position: center center;
            background-size: cover;
            position: fixed;
            top: 120px;
            bottom: 0;
            left: 0;
            right: 0;
            background-repeat: no-repeat;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            /* transition: opacity 0.9s ease; */
        }

        .greet-modal {
            background: rgba(3, 5, 4, .8);
            text-align: center;
            width: 100%;
            max-width: 900px;
            padding: 30px 30px 60px;
            margin: 0 auto;
        }

        .greet-modal-logo {
            text-align: right;
        }

        .greet-modal-logo img {
            width: 60px;
        }

        .greet-modal .logo {

            color: #fff;
            display: block;
            font-family: "The Bredan Demo", sans-serif;
            font-size: 70px;
            font-weight: 500;
            line-height: 1;
            margin-bottom: 30px;

        }

        .greet-modal p {

            color: #fff;
            font-family: 'MrsEavesOT-Roman', sans-serif;
            font-size: 22px;
            line-height: 1.4;
            margin-bottom: 20px;

        }

        .greet-modal .btn:hover {
            border-color: #ae894f;
            background: #ae894f;
        }

        .greet-modal .btn {
            appearance: none;
            background: #fff;
            border: 1px solid #fff;
            border-radius: 0;
            color: #000;
            cursor: pointer;
            display: inline-block;
            font-family: 'mrs-eaves';
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            margin: 0 10px;
            padding: 6px 23px;
            text-align: center;
            text-decoration: none;
            text-transform: uppercase;
            transition: all 0.6s ease;
            user-select: none;
            vertical-align: middle;
            white-space: normal;
            width: auto;

        }

        @media (max-width: 576px) {
            .greet-modal-logo {
                margin-bottom: 20px;
            }

        }
    </style>
    <?php $this->head() ?>
</head>

<body class=" ">

    <?php $this->beginBody() ?>
    <div class="trigger"></div>
    <header class="header">
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
                                            <?= Yii::$app->user->isGuest ? 'My Account' : 'Hi,' . Yii::$app->user->identity->first_name ?>
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
                                <span class="header-links-link header-links-link-ship">
                                    <div class="flex flex-column ">
                                        <div for="ship-to" class="header-links-text hide-mobile">
                                            Ship To:
                                        </div>
                                        <div class="header-links-icon">
                                            <select id="ship-to" class="select-scroll select">
                                                <option value="">...</option>
                                                <option value="AK">AK</option>
                                                <option value="AL">AL</option>
                                                <option value="AR">AR</option>
                                                <option value="AZ">AZ</option>
                                                <option value="CA">CA</option>
                                                <option value="CO">CO</option>
                                                <option value="CT">CT</option>
                                                <option value="DC">DC</option>
                                                <option value="DE">DE</option>
                                                <option value="FL">FL</option>
                                                <option value="GA">GA</option>
                                                <option value="IA">IA</option>
                                                <option value="ID">ID</option>
                                                <option value="IL">IL</option>
                                                <option value="IN">IN</option>
                                                <option value="IO">IO</option>
                                                <option value="KS">KS</option>
                                                <option value="KY">KY</option>
                                                <option value="LA">LA</option>
                                                <option value="MA">MA</option>
                                                <option value="MD">MD</option>
                                                <option value="ME">ME</option>
                                                <option value="MI">MI</option>
                                                <option value="MN">MN</option>
                                                <option value="MO">MO</option>
                                                <option value="MT">MT</option>
                                                <option value="NC">NC</option>
                                                <option value="ND">ND</option>
                                                <option value="NE">NE</option>
                                                <option value="NH">NH</option>
                                                <option value="NJ">NJ</option>
                                                <option value="NM">NM</option>
                                                <option value="NV">NV</option>
                                                <option value="NY">NY</option>
                                                <option value="OH">OH</option>
                                                <option value="OK">OK</option>
                                                <option value="OR">OR</option>
                                                <option value="PA">PA</option>
                                                <option value="RI">RI</option>
                                                <option value="SC">SC</option>
                                                <option value="SD">SD</option>
                                                <option value="TN">TN</option>
                                                <option value="TX">TX</option>
                                                <option value="UT">UT</option>
                                                <option value="VA">VA</option>
                                                <option value="VT">VT</option>
                                                <option value="WA">WA</option>
                                                <option value="WI">WI</option>
                                                <option value="WV">WV</option>
                                                <option value="WY">WY</option>
                                            </select>
                                        </div>
                                    </div>
                                </span>

                            </li>
                            <?php if (!Yii::$app->user->isGuest) : ?>
                                <li class="header-links-item header-links-ship">
                                    <a href="<?= Url::toRoute(['/sign/out']) ?>" class="header-links-link">
                                        <div class="header-links-icon">
                                        </div>
                                        <div class="header-links-text hide-mobile">
                                            Logout
                                        </div>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>



                    </div>
                    <div class="header-mobile">
                        <div class="header-icon header-icon-search js-show-searchbar">
                            <!-- <img src="/images/close-search.png" class="img-search-close"> -->

                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="search" role="img" viewBox="0 0 512 512" class="img-search-open svg-inline--fa fa-search fa-w-16 fa-3x">
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
                                                    <img src="/images/login-gold.png" alt="">
                                                </div>
                                                <div class="header-links-text  ">
                                                    <?= Yii::$app->user->isGuest ? 'My Account' : 'Hi,' . Yii::$app->user->identity->first_name ?>
                                                </div>
                                                </a>

                                    </li>

                                    <li class="header-links-item">
                                        <a href="<?= Url::toRoute(['/site/contactus']) ?>" class="header-links-link" data-toggle="tooltip" data-placement="top" data-original-title=" Customer support">
                                            <div class="header-links-icon">
                                                <img src="/images/support-gold.png" alt="">

                                            </div>

                                            <div class="header-links-text ">
                                                Support
                                            </div>
                                        </a>

                                    </li>
                                    <li class="header-links-item header-links-item-cart">
                                        <a href="<?= Url::toRoute(['/shop/cart/cart']) ?>" class="header-links-link">
                                            <div class="header-links-icon">
                                                <img src="/images/header-cart.png" alt="">
                                                <span class="header-links-qty js-count-cart"><?= Yii::$app->cart->count ?></span>
                                            </div>
                                            <div class="header-links-text pl-2">
                                                <span class=" js-cost-cart "><?= Yii::$app->formatter->asCurrency(Yii::$app->cart->cost) ?></span>
                                            </div>
                                        </a>

                                    </li>
                                    <?php if (!Yii::$app->user->isGuest) : ?>
                                        <li class="header-links-item">
                                            <a href="<?= Url::toRoute(['/sign/out']) ?>" class="header-links-link">
                                                <div class="header-links-icon">
                                                    <img src="/images/login-gold.png" alt="">
                                                </div>
                                                <div class="header-links-text ">
                                                    Logout
                                                </div>
                                            </a>

                                        </li>
                                    <?php endif; ?>
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

    <?= $content ?>

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

    <div class="modal fade" id="exampleModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content" style="border: none;background: none">
                <div class="loader">Loading...</div>
                <div class="modal-body">
                    <h3> <strong id="js-loader-text" class="card-link mt-auto" style="color: #ae894f">Please
                            wait ...</strong></h3>
                </div>
            </div>
        </div>
    </div>
    <a href="#" id="to-top" class="to-top  ">
        <svg class="svg-inline--fa fa-chevron-up fa-w-14" aria-hidden="true" data-prefix="fas" data-icon="chevron-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
            <path fill="currentColor" d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"></path>
        </svg><!-- <i class="fas fa-chevron-up"></i> -->
    </a>
    <!--
    <a href="#" id="to-top" class="to-top up-btn js-up-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="22.845" height="12.322" viewBox="0 0 22.845 12.322">
            <g id="Group_181" data-name="Group 181" transform="translate(-490.057 -525.777)">
                <line id="Line_56" data-name="Line 56" x1="11.574" y1="10.633" transform="translate(500.552 526.622)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2.294" />
                <line id="Line_57" data-name="Line 57" y1="10.633" x2="11.574" transform="translate(490.833 526.622)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2.294" />
            </g>
        </svg>
    </a> -->
    <div class="greet" style="background-image: url(<?= imageBlock() ?>)">
        <div class="greet-modal">
            <div class="greet-modal-logo">
                <img src="/images/logo-sm.svg" alt="logo">

            </div>
            <div class="text-center">
                <span class="logo">
                    <img src="/images/logo-wh.png" style="max-width:210px " alt="">

                </span>
                <p>Selected Brands</p>
                <p>I am over the age of 21 and agree to the terms of use for this site.</p>
                <div class="greet-btns">
                    <a href="#" class="js-verify-yes btn">I’m Over 21</a>
                    <a href="#" class="js-verify-no btn">Exit</a>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>

<?php

$js = <<< JS


JS;
$this->registerJs($js);
?>

</html>
<?php $this->endPage() ?>