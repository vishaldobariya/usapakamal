<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use trntv\filekit\widget\Upload;
use app\modules\shop\models\Category;

/* @var $this yii\web\View */
/* @var $model app\modules\shop\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'image')->widget(Upload::class, [
		'url'                 => ['/storage/default/upload'],
		'uploadPath'          => 'images/categories/',
		'sortable'            => true,
		'maxNumberOfFiles'    => 2,
		'showPreviewFilename' => false,
	])->label('Image'); ?>
	
	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
	<?= $form->field($model, 'status')->dropDownList(Category::STATUSES, ['prompt' => 'Select status ...']) ?>
	<?= $form->field($model, 'parent_id', ['options' => ['class' => 'mt-5 mb-3']])->widget(Select2::classname(), [
		'data'          => $catParents,
		'options'       => ['placeholder' => 'Select a category ...'],
		'pluginOptions' => [
			'allowClear' => true,
		],
	])->label('Assigned to Category') ?>
	
	<?= $form->field($model, 'product_ids')->widget(Select2::class, [
		'data'          => $data,
		'size'          => Select2::LARGE,
		'theme'         => Select2::THEME_BOOTSTRAP,
		'options'       => [
			'placeholder' => 'Select a products...',
			'multiple'    => true,
			'value'       => $products ?? [],
		],
		'pluginOptions' => [
			'allowClear'      => true,
			'tags'            => true,
			'closeOnSelect'   => false,
			'tokenSeparators' => [','],
			'ajax'            => [
				'url'      => '/shop/category/find-prods',
				'dataType' => 'json',
				'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
			],
		],
	
	])->label('Assigned Products') ?>

	<div class="form-group">
		<?= Html::submitButton('Save', ['class' => 'btn btn-primary mt-4']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>

