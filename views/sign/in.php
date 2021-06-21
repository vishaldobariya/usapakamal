<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;

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
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'remember')->checkbox() ?>
	
	<?= Html::submitButton('Sign In', ['class' => 'btn btn-primary']) ?>
	
	
	<?php ActiveForm::end() ?>
			</div>
	            <div class="d-flex justify-content-between mb-3">
		         <a href="<?= Url::toRoute(['/sign/forgot']) ?>">Forgot password?</a>
		         <a href="<?= Url::toRoute(['/sign/register-customer']) ?>">Register as customer</a>
	           </div>
			</div>
        </div>
    </div>
</main>
