<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
?>
<main class="page products">
    <div class="container">
        <div class="row catalogue-inner">
            <div class="col-sm-6 col-lg-4   catalogue-item">
            </div>
            <div class="col-sm-6 col-lg-4   catalogue-item">
               <div class="py-5 main-screen align-items-stretch">
	<?php $form = ActiveForm::begin() ?>
	
	<?= $form->field($model, 'email') ?>
	<?= $form->field($model, 'first_name') ?>
	<?= $form->field($model, 'last_name') ?>
	<?= $form->field($model, 'zip')->widget(MaskedInput::class, [
		'mask'    => '9{5}',
		'options' =>
			[
				'placeholder' => '11111',
			
			],
	]) ?>
	<?= $form->field($model, 'phone')->widget(MaskedInput::class, [
		'mask'    => '(9{3}) 9{3}-9{4}',
		'options' =>
			[
				'placeholder' => '(123) 123-1234',
			
			],
	]) ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'confirm_password')->passwordInput() ?>
	
	
	<?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary']) ?>
	
	
	<?php ActiveForm::end() ?>
			</div>
	            <div class="d-flex justify-content-between mb-3">
		         <a href="<?= Url::toRoute(['/sign/forgot']) ?>">Forgot password?</a>
		         <a href="<?= Url::toRoute(['/sign/register-customer']) ?>">Sign in</a>
	           </div>
			</div>
        </div>
    </div>
</main>
