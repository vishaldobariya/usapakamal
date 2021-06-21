<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\shop\models\Coupon;

/* @var $this yii\web\View */
/* @var $model app\modules\shop\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-form">

	<?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-6">
			<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
		</div>

		<div class="col-md-6">
			<?= $form->field($model, 'value')->input('number', ['step' => 0.00001, 'min' => 0.00001]) ?>
		</div>
		<div class="col-md-6 mt-3">
			<div class="form-check">
				<input class="form-check-input" type="radio" name="Coupon[type]" id="exampleRadios1" value="is_products_with_ship" <?= !$model->isNewRecord && $model->is_products_with_ship ? 'checked' : '' ?> <?= $model->isNewRecord ? 'checked' : '' ?>>
				<label class="form-check-label" for="exampleRadios1">
					For Products with Ship
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="radio" name="Coupon[type]" id="exampleRadios2" value="is_only_products" <?= !$model->isNewRecord && $model->is_only_products ? 'checked' : '' ?>>
				<label class="form-check-label" for="exampleRadios2">
					For only Products
				</label>
			</div>
			<div class="form-check disabled">
				<input class="form-check-input" type="radio" name="Coupon[type]" id="exampleRadios3" value="is_only_ship" <?= !$model->isNewRecord && $model->is_only_ship ? 'checked' : '' ?>>
				<label class="form-check-label" for="exampleRadios3">
					For only Ship
				</label>
			</div>
		</div>
		<div class="col-md-6 mt-3">
			<div class="form-check">
				<input class="form-check-input" type="radio" name="Coupon[num]" value="0" id="defaultCheck1" <?= !$model->isNewRecord && $model->is_percent ? 'checked' : '' ?> <?= $model->isNewRecord ? 'checked' : '' ?>>
				<label class="form-check-label" for="defaultCheck1">
					%
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="radio" name="Coupon[num]" value="1" id="defaultCheck2" <?= !$model->isNewRecord && $model->is_usd ? 'checked' : '' ?>>
				<label class="form-check-label" for="defaultCheck2">
					$
				</label>
			</div>
		</div>
		<div class="col-md-6 mt-3">
			<?= $form->field($model, 'start_date')->input('date', ['value' => $model->isNewRecord ? '' : date('Y-m-d', $model->start_date)]) ?>
		</div>
		<div class="col-md-6 mt-3">
			<?= $form->field($model, 'end_date')->input('date', ['value' => $model->isNewRecord ? '' : date('Y-m-d', $model->end_date)]) ?>
		</div>
		<div class="col-md-6 mt-3">
			<?= $form->field($model, 'status')->dropDownList(Coupon::STATUSES) ?>
		</div>
		<div class="col-md-6 mt-3">
			<?= $form->field($model, 'min_cart_price')->input('number', ['step' => 0.00001, 'min' => 0.00000])->hint('the cost of all products in the cart will be calculated') ?>
		</div>
		<div class="col-md-12 mt-3">
			<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
		</div>

		<div class="col-md-12 mt-3">
			<div class="form-group mt-3">
				<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>

</div>