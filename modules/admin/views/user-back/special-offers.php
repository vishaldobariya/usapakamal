<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use app\modules\shop\models\Product;
use yii\helpers\Url;

?>
<?php if (!empty($offers)) : ?>
    <div class="collection ">
        <h2 class="title w-100">FEATURED BRANDS</h2>
        <div class="collection-inner padding-bottom">
            <div class="container">
                <div class="grid row">
                    <?php foreach ($offers as $product)
                        /**
	                     * @var $product Product
	                     */
                        : ?>

                        <div class="col-lg-3 col-sm-6 col-6 collection-item">
                            <div class="product-card js-product-card   <? if($product->isSold()) {
	                            echo 'product-card-sold';
                            }; ?>">
                                <div class="product-card-img">
                                    <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $product->slug]) ?>">
                                        <img src="<?= $product->thumb ?>" class="img js-product-img" alt="" loading="lazy"></a>
                                    <div class="product-action">

                                        <div class=" product-action-logo">
                                            <img src="/images/logo-sm.svg" alt="">
                                        </div>
                                        <div class="product-action-tags">
                                            <?php if ($product->isNew()) : ?>
                                                <div class="product-action-tag product-action-new">

                                                </div>

                                            <?php endif; ?>

                                            <?php if ($product->isSold()) : ?>
                                                <div class="product-action-tag product-action-sold">

                                                </div>

                                            <?php endif; ?>
                                            <?php if ($product->isAvailableWithEngraving()) : ?>

                                                <div class="product-action-tag product-action-custom">

                                                </div>

                                            <?php endif; ?>
                                            <?php if ($product->isSale()) : ?>
                                                <div class="product-action-tag product-action-sale">

                                                </div>

                                            <?php endif; ?>
                                            <?php if ($product->isSpecialPromotion()) : ?>
                                                <div class="product-action-tag product-action-special">

                                                </div>

                                            <?php endif; ?>
                                            <?php if ($product->isLimitedEdition()) : ?>
                                                <div class="product-action-tag product-action-limited">

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-card-descr">
                                    <h3 class="product-card-title product-card-title-sm  ">
                                        <?= ($product->brand->name ?? '') ?></h3>
                                    <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $product->slug]) ?>">
                                        <h3 class="product-card-title fixed"><?= $product->name ?></h3>
                                    </a>
                                    <!-- <div class="product-card-text">

                                    <? //= $product->description ?>
                                </div> -->
                                    <div class=" mt-auto">
                                        <?php if ($product->vol || $product->abv) : ?>
                                            <div class="product-card-tags">
                                                <?= $product->vol ? $product->vol . 'ml' : '' ?><?= $product->abv ? ' | ' . $product->abv . '% ABV' : '' ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="product-card-tags">
                                            <?= ($product->category->name ?? '') . ($product->subCategory ? ' | ' . $product->subCategory->name : '') ?>
                                        </div>
                                    </div>

                                    <div class="product-card-footer flex-wrap">
                                        <div class="product-card-price">
                                            <?php if ($product->isSale()) : ?>
                                                <div class="product-card-oldprice">
                                                    <span><?= Yii::$app->formatter->asCurrency($product->price) ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <span><?= Yii::$app->formatter->asCurrency($product->getPrice()) ?></span>

                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>