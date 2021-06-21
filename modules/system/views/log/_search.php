<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\search\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin([
	'action'  => ['index'],
	'method'  => 'get',
	'options' => [
		'data-pjax' => 1,
	],
]
	); ?>

<?= $form->field($model, 'search', [
	'addon' => [
		'append' => [
			'content'  => Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-sm btn-light']),
			'asButton' => true,
		],
	],
])->label(false) ?>

<?php ActiveForm::end(); ?>

