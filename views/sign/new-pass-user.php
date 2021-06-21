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
use app\modules\user\models\Forgot;

/**
 * @var $model Forgot
 */
?>
<main class="page products">
    <div class="container">
        <div class="row catalogue-inner">
            <div class="col-sm-6 col-lg-4   catalogue-item">
            </div>
            <div class="col-sm-6 col-lg-4   catalogue-item">
               <div class="py-5 main-screen align-items-stretch">
	<?php $form = ActiveForm::begin() ?>
	<?= $form->field($model, 'new_password')->passwordInput() ?>
	<?= $form->field($model, 'confirm_password')->passwordInput() ?>
	<?= Html::submitButton('Apply', ['class' => 'btn btn-primary']) ?>
	
	<?php ActiveForm::end() ?>
</div>
            </div>
	       
        </div>
	    
    </div>
</main>
