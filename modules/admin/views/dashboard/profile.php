<?php

use app\modules\admin\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

DashboardAsset::register($this);

$this->title = 'Profile';

?>
<?php if ($model->role == 'distributor') : ?>
  <div class="row" id="dragdrop">
    <div class="col-md-4 col-sm-12">
	    <a href="<?=Url::toRoute(['/shop/store/my-products'])?>">
      <div class="card">
        <div class="card-body">
          <div class="stat-widget-two">
            <div class="media">
              <div class="media-body">
                <h2 class="mt-0 mb-1 text-info"><?= $prods ?></h2><span class="">Products</span>
              </div>
              <img class="ml-3" src="/admin/images/1.png" alt="">
            </div>
          </div>
        </div>
      </div>
		    </a>
    </div>
    <div class="col-md-4 col-sm-12">
	    <a href="<?=Url::toRoute(['/shop/store/my-products','StoreProductSearch[connected]' => 0])?>">
      <div class="card">
        <div class="card-body">
          <div class="stat-widget-two">
            <div class="media">
              <div class="media-body">
                <h2 class="mt-0 mb-1 text-danger"><?= $not_con_prods ?></h2><span class="">Not Connected Products</span>
              </div>
              <img class="ml-3" src="/admin/images/2.png" alt="">
            </div>
          </div>
        </div>
      </div>
	    </a>
    </div>
    <div class="col-md-4 col-sm-12">
	    	    <a href="<?=Url::toRoute(['/shop/store/my-products','StoreProductSearch[connected]' => 1])?>">
      <div class="card">
        <div class="card-body">
          <div class="stat-widget-two">
            <div class="media">
              <div class="media-body">
                <h2 class="mt-0 mb-1 text-warning"><?= $con_prods ?></h2><span class="">Connected Products</span>
              </div>
              <img class="ml-3" src="/admin/images/3.png" alt="">
            </div>
          </div>
        </div>
      </div>
		        </a>
    </div>
  </div>
<?php endif; ?>
<div class="row">
  <div class="col-xl-8 col-xxl-7 col-lg-8">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title m-t-10">Personal Data</h4>
        <div class="table-action ">
          <?php $form = ActiveForm::begin(); ?>
          <?= $form->field($model, 'first_name') ?>
          <?= $form->field($model, 'last_name') ?>
          <?= $form->field($model, 'email') ?>
          <?= $form->field($model, 'new_pass')->label('Password') ?>
          <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
            'mask'    => '(9{3}) 9{3}-9{4}',
            'options' =>
            [
              'placeholder' => '(123) 123-1234',

            ],
          ]) ?>
          <?= $form->field($model, 'store_name', ['options' => [
            'class' => 'mb-2'
          ]]) ?>

          <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary mt-3']) ?>
          </div>

          <?php ActiveForm::end(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
