<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use app\modules\shop\models\Product;

?>

<div class="index page">

    <div class="hero-wrapper ">
        <div class="hero hero-slider hero-slider-lg d-none d-sm-block">
            <?php foreach ($sliders as $slider) : ?>
                <div class="hero-slide slide  " style="background-image: url(<?= $slider->src ?>)">

                </div>
            <?php endforeach; ?>

        </div>
        <div class="hero hero-slider hero-slider-sm d-block d-sm-none">
            <?php foreach ($mobile_sliders as $mobile) : ?>
                <div class="hero-slide slide " style="background-image: url(<?= $mobile->src ?>)">

                </div>
            <?php endforeach; ?>

        </div>
        <div class="hero-slider-progress">
            <div class="hero-progress"></div>
        </div>
    </div>

    <?php if (!empty($offers)) : ?>
        <div class="products padding">
            <div class="container container-full">
                <h1 class="title">SPECIAL OFFERS</h1>
                <div class="position-relative products-slider-wrap">
                    <div class="products-slider js-products-slider grid row  glider">

                        <?php foreach ($offers as $offer)
                            /**
	                         * @var $offer Product
	                         */
                            : ?>
                            <div class=" collection-item">
                                <div class="product-card js-product-card <? if ($offer->isSold()) {
                                                                                echo 'product-card-sold';
                                                                            }; ?>">
                                    <div class="product-card-img">
                                        <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $offer->slug]) ?>" class="product-card-img-link">
                                            <img src="<?= $offer->thumb ?>" loading="lazy" class="img js-product-img" alt="/">
                                        </a>
                                        <div class="product-action">

                                            <div class=" product-action-logo">
                                                <img src="/images/logo-sm.svg" alt="/">
                                            </div>
                                            <div class="product-action-tags">


                                                <?php if ($offer->isNew()) : ?>
                                                    <div class="product-action-tag product-action-new">

                                                    </div>

                                                <?php endif; ?>
                                                <?php if ($offer->isSold()) : ?>
                                                    <div class="product-action-tag product-action-sold">

                                                    </div>

                                                <?php endif; ?>
                                                <?php if ($offer->isAvailableWithEngraving()) : ?>

                                                    <div class="product-action-tag product-action-custom">
                                                    </div>

                                                <?php endif; ?>
                                                <?php if ($offer->isSale()) : ?>
                                                    <div class="product-action-tag product-action-sale">

                                                    </div>

                                                <?php endif; ?>
                                                <?php if ($offer->isSpecialPromotion()) : ?>
                                                    <div class="product-action-tag product-action-special">

                                                    </div>

                                                <?php endif; ?>
                                                <?php if ($offer->isLimitedEdition()) : ?>
                                                    <div class="product-action-tag product-action-limited">

                                                    </div>

                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="product-card-descr">
                                        <h3 class="product-card-title  product-card-title-sm"><?= ($offer->brand->name ?? '') ?>
                                        </h3>
                                        <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $offer->slug]) ?>">
                                            <h3 class="product-card-title fixed"><?= $offer->name ?></h3>
                                        </a>
                                        <!-- <div class="product-card-text">
                                <? //= $offer->description
                                ?>
                            </div> -->
                                        <div class=" mt-auto">
                                            <?php if ($offer->vol || $offer->abv) : ?>
                                                <div class="product-card-tags"><?= $offer->vol ? $offer->vol . 'ml' : '' ?><?= $offer->abv ? ' | ' . $offer->abv . '% ABV' : '' ?></div>
                                            <?php endif; ?>
                                            <div class="product-card-tags">
                                                <?= ($offer->category->name ?? '') . ($offer->subCategory ? ' | ' . $offer->subCategory->name : '') ?>
                                            </div>
                                        </div>

                                        <div class="product-card-footer flex-wrap">
                                            <div class="product-card-price">

                                                <?php if ($offer->isSale()) : ?>
                                                    <div class="product-card-oldprice">
                                                        <span>
                                                            <?= Yii::$app->formatter->asCurrency($offer->price) ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <span>

                                                    <?= Yii::$app->formatter->asCurrency($offer->getPrice()) ?>
                                                </span>


                                            </div>
                                            <div class="text-center">
                                                <?php if (!$offer->isSold()) : ?>
                                                    <a href="#" class="product-card-cart js-add-to-cart" data-qty="1" data-id="<?= $offer->id ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add to Cart" tabindex="0">

                                                        <!-- <img src="/images/cart.svg" alt="/"> -->
                                                    </a>
                                                    <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $offer->slug]) ?>" class="btn btn-primary">
                                                        SHOP NOW
                                                    </a>
                                                <?php else : ?>
                                                    <span class="btn btn-primary btn-sold">

                                                        SOLD OUT
                                                    </span>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>


                    </div>

                    <div class="products-slider-arrows">
                        <button aria-label="Previous" class="glider-prev">«</button>
                        <button aria-label="Next" class="glider-next">»</button>
                    </div>
                </div>

                <div class="text-center show-more">
                    <a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[special_offers]' => 1]) ?>">Show
                        More Special Offers</a>
                </div>

            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($middle_images)) : ?>
        <div class="tout    ">
            <div class="container">
                <div class="tout-inner">
                    <div class="tout-grid">
                        <?php

                        $last = null;
                        if ($middle_images % 2 !== 0) {
                            $last = array_pop($middle_images);
                        } ?>
                        <?php foreach ($middle_images as $middle) : ?>

                            <!-- <div class="col-md-6"> -->
                            <a href="<?= $middle->link ?>" class="tout-item d-block w-100 h-100   bg-black">
                                <div class="tout-img " style="background-image:url(<?= $middle->src ?>)">
                                </div>
                            </a>
                            <!-- </div> -->
                        <?php endforeach; ?>
                    </div>
                    <?php if ($last) : ?>
                        <div class="w-100 pt-10 tout-last">
                            <a href="<?= $last->link ?>" class="tout-item d-block w-100 h-100">

                                <div class="tout-img tout-img-full " style="background-image:url(<?= $last->src ?>)">
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    <?php endif; ?>
    <div class="collection ">
        <div class="collection-inner padding-bottom">
            <div class="container">
                <h2 class="title">FEATURED BRANDS</h2>
                <div class="grid row">
                    <?php foreach ($products as $product)
                        /**
	                     * @var $product Product
	                     */
                        : ?>

                        <div class="col-lg-3 col-sm-6 col-6 collection-item">
                            <div class="product-card  js-product-card <? if ($product->isSold()) {
                                                                            echo 'product-card-sold';
                                                                        }; ?>">
                                <div class="product-card-img">
                                    <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $product->slug]) ?>">
                                        <img src="<?= $product->thumb ?>" class="img js-product-img" alt="/" loading="lazy"></a>
                                    <div class="product-action">

                                        <div class=" product-action-logo">
                                            <img src="/images/logo-sm.svg" alt="/">
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

                                    <? //= $product->description
                                    ?>
                                </div> -->
                                    <div class=" mt-auto">
                                        <?php if ($product->vol || $product->abv) : ?>
                                            <div class="product-card-tags"><?= $product->vol ? $product->vol . 'ml' : '' ?><?= $product->abv ? ' | ' . $product->abv . '% ABV' : '' ?></div>
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

                                        <div class="text-center">
                                            <a href="#" class="product-card-cart js-add-to-cart" data-qty="1" data-id="<?= $product->id ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add to Cart" tabindex="0">

                                                <!-- <img src="/images/cart.svg" alt="/"> -->
                                            </a>
                                            <?php if (!$product->isSold()) : ?>

                                                <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $product->slug]) ?>" class="btn btn-primary btn-shop">

                                                    SHOP NOW
                                                </a>
                                            <?php else : ?>
                                                <span class="btn btn-primary btn-sold">

                                                    SOLD OUT
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center show-more">
                    <a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[featured_brand]' => 1]) ?>">Show
                        More Featured Brands</a>
                </div>
            </div>

        </div>
    </div>
    <?php if (!empty($footer_images)) : ?>
        <div class="tout    ">
            <div class="container">
                <div class="tout-inner">
                    <div class="tout-grid">
                        <?php

                        $last = null;
                        if ($footer_images % 2 !== 0) {
                            $last = array_pop($footer_images);
                        } ?>
                        <?php foreach ($footer_images as $footer) : ?>


                            <a href="<?= $footer->link ?>" class="tout-item d-block w-100 h-100   bg-black">
                                <div class="tout-img " style="background-image:url(<?= $footer->src ?>)">
                                </div>
                            </a>

                        <?php endforeach; ?>
                    </div>
                    <?php if ($last) : ?>
                        <div class="w-100 pt-10 tout-last">
                            <a href="<?= $last->link ?>" class="tout-item d-block w-100 h-100">

                                <div class="tout-img tout-img-full " style="background-image:url(<?= $last->src ?>)">
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    <?php endif; ?>
    <div class="brands padding">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <?php foreach ($brands as $brand) : ?>
                    <div class="col-md-2 col-sm-4 col-6 d-flex align-items-center px-2 px-md-4 brand-item">
                        <a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[brand_id]' => [$brand->id]]) ?>" class="brands-item brands-chivas" style="background-image: url(<?= $brand->thumb ?>);">
                        </a>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <div class="about padding mb-4 d-sm-none">
        <div class="container text-center">
            <a href="<?= Url::toRoute(['/site/brands']) ?>" class="btn  btn-secondary">See More Brands</a>
        </div>
    </div>

</div>