<?php

use yii\helpers\Url;
use kartik\form\ActiveForm;
use trntv\filekit\widget\Upload;


?>
<div class=" ">

    <ul class="breadcrumb mt-4 ">

        <li class="breadcrumb-item"><a href="<?= Url::toRoute(['/admin/dashboard/welcome']) ?>">Welcome <span class="mr-1"></span></a>
        </li>

        <li class="breadcrumb-item active" aria-current="page"></li>

    </ul>
    <div class="container">
        <div class="text-center mt-5 mb-5">
            <h2>MY ACCOUNT </h2>

        </div>
        <div class="row justify-content-center mb-5">

            <div class="col-sm-5 col-lg-3 d-flex flex-column h-100 align-items-center justify-content-center">
                <a href="<?= Url::toRoute(['/shop/order/user-index']) ?>" class="btn btn-grey btn-outline mb-3 w-100">ORDER HISTORY</a>
                <a href="<?= Url::toRoute(['/shop/order/user-saved-index']) ?>" class="btn btn-grey btn-outline mb-3 w-100">SAVED ORDERS</a>
                <a href="<?= Url::toRoute(['/admin/dashboard/user-profile']) ?>" class="btn btn-grey btn-outline mb-3 w-100">PROFILE</a>
                <a href="<?= Url::toRoute(['/sign/out']) ?>" class="btn btn-grey btn-outline mb-3 w-100">LOG OUT</a>
            </div>
        </div>
    </div>
</div>
