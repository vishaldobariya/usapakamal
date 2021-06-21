<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

	<?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-6">
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
		</div>
		<div class="col-md-6">

			<?= $form->field($model, 'role')->dropDownList(['user' => 'Customer', 'distributor' => 'Distributor', 'admin' => 'Admin'], ['prompt' => 'Select Role']) ?>
			<?= $form->field($model, 'store_name', ['options' => []]) ?>
			<div class="mt-5 checkbox-wrap">
				<?= $form->field($model, 'confirmed')->checkbox() ?>
				<?= $form->field($model, 'blocked')->checkbox() ?>
			</div>
			
			<?= $form->field($model, 'zip') ?>
		</div>
	</div>





	<div class="form-group mt-3">
		<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>
