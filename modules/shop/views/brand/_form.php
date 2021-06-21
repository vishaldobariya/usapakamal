<?php

use trntv\filekit\widget\Upload;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\shop\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brand-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->widget(Upload::class, [
        'url'                 => ['/storage/default/upload'],
        'uploadPath'          => 'images/brands/',
        'sortable'            => true,
        'maxNumberOfFiles'    => 2,
        'showPreviewFilename' => false,
    ])->label('Image'); ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'position')->input('number', ['min' => 1, 'step' => 1]) ?>
    <div class="checkbox-wrap">
        <?= $form->field($model, 'main')->checkbox(['class' => 'mt-4'])->label(false) ?>

    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary mt-4']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>