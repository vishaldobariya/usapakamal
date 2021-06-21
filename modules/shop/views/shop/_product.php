<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use yii\helpers\Url;
use app\modules\shop\models\Product;

/**
 * @var $model Product
 */
?>
<div class=" fb-20 collection-item">
    <div class="product-card js-product-card d-flex flex-column h-100    <? if($model->isSold()) {
	    echo 'product-card-sold';
    }; ?>">
        <div class="product-card-img">
            <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $model->slug]) ?>" class="product-card-img-link">
                <img src="<?= $model->thumb ?>" class="img js-product-img" loading="lazy" alt="">
            </a>
            <div class="product-action">

                <div class=" product-action-logo">
                    <img src="/images/logo-sm.svg" alt="">
                </div>
                <div class="product-action-tags">


                    <?php if ($model->isSold()) : ?>
                        <div class="product-action-tag product-action-sold">

                        </div>

                    <?php endif; ?>
                    <?php if ($model->isAvailableWithEngraving()) : ?>

                        <div class="product-action-tag product-action-custom">

                        </div>

                    <?php endif; ?>
                    <?php if ($model->isSale()) : ?>
                        <div class="product-action-tag product-action-sale">

                        </div>

                    <?php endif; ?>
                    <?php if ($model->isSpecialPromotion()) : ?>
                        <div class="product-action-tag product-action-special">

                        </div>

                    <?php endif; ?>
                    <?php if ($model->isLimitedEdition()) : ?>
                        <div class="product-action-tag product-action-limited">

                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="product-card-descr  ">
            <p class="product-card-title product-card-title-sm  "><?= ($model->brand->name ?? '') ?></p>
            <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $model->slug]) ?>">
                <h3 class="product-card-title fixed"><?= $model->name ?></h3>
            </a>
            <!-- <div class="product-card-text">
                <? //= $model->description ?>
            </div> -->
            <div class=" mt-auto">
                <?php if ($model->vol || $model->abv) : ?>
                    <div class="product-card-tags"><?= $model->vol ? $model->vol . 'ml' : '' ?><?= $model->abv ? ' | ' . $model->abv . '% ABV' : '' ?></div>
                <?php endif; ?>
                <div class="product-card-tags">
                    <?= ($model->category->name ?? '') . ($model->subCategory ? ' | ' . $model->subCategory->name : '') ?>
                </div>
            </div>

            <div class="product-card-footer">
                <div class="product-card-price">
                    <?php if ($model->isSale()) : ?>
                        <div class="product-card-oldprice">
                            <span><?= Yii::$app->formatter->asCurrency($model->price) ?></span>
                        </div>
                    <?php endif; ?>
                    <span> <?= Yii::$app->formatter->asCurrency($model->getPrice()) ?></span>

                </div>
                <div class="text-center">
                    <a href="#" class="product-card-cart js-add-to-cart" data-qty="1" data-id="<?= $model->id ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add to Cart" tabindex="0">

                        <!-- <img src="/images/cart.svg" alt=""> -->
                    </a>
                    <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $model->slug]) ?>" class="btn btn-primary btn-shop">

                        SHOP NOW
                    </a>
                    <span class="btn btn-primary btn-sold disabled" disabled>

                        SOLD OUT
                    </span>
                </div>
            </div>



        </div>

    </div>

</div>