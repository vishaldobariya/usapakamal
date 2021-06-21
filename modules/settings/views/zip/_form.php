<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\Zip */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="zip-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>

            <div class="checkbox-wrap">
                <?= $form->field($model, 'active')->checkbox() ?>
            </div>

        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>