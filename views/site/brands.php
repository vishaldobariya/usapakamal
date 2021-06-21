<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use app\modules\shop\models\Product;

?>
<div class="container page brands brands-page">
    <div class="row py-5">
        <?php foreach ($brands as $brand) : ?>
            <div class="col-md-2 col-sm-4 col-6 d-flex align-items-center px-2 px-md-4 brand-item">
                <a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[brand_id]' => [$brand->id]]) ?>" class="brands-item brands-chivas" style="background-image: url(<?= $brand->thumb ?>);">
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>