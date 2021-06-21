<?php
/**
 * _search.php
 *
 * @author     Paul Storre <1230840.ps@gmail.com>
 * @package    AX project
 * @version    1.0
 * @copyright  IndustrialAX LLC
 * @license    https://industrialax.com/license
 * @since      File available since v1.0
 */

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $form \yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(
	[
		'action'  => ['my-products'],
		'method'  => 'get',
		'options' => ['data-pjax' => 1],
		'id'      => 'js-search',
	]
); ?>

<?= $form->field($model, 'search',
	[
		'options' => ['class' => 'grid-form-group'],
		'addon'   => [
			'append' => [
				'content'  => Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-white']),
				'asButton' => true,
			],
		],
	]
)->input('text', [
		'style' => 'min-width: 400px !important',
		'id'    => 'js-search-input',
		'class' => 'filters',
		//'value' => Yii::$app->request->get('StoreProductSearch') ? Yii::$app->request->get('StoreProductSearch')['product_name'] : '',
	]
//	Select2::class, [
//	'options'       => ['placeholder' => 'Search a product ...',],
//	'pluginEvents' => [
//		"select2:select"      => "function() { $('#js-search').submit() }",
//		"select2:unselect"    => "function() { $('#js-search').submit() }",
//	],
//	'pluginOptions' => [
//		'allowClear'         => true,
//		'minimumInputLength' => 3,
//		'language'           => [
//			'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
//		],
//
//		'close'  => "function() { console.log('close'); }",
//		'select' => "function() { console.log('select'); }",
//
//		'ajax' => [
//			'url'      => '/shop/store/find-products',
//			'dataType' => 'json',
//			'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
//		],
//	],
//]
)->label(false) ?>
<?php ActiveForm::end(); ?>

<?php

$css = <<< CSS
.select2.select2-container{
	min-width: 400px !important;
}
.select2-selection.select2-selection--single {
display: flex;
align-items: center;
}
CSS;

$this->registerCss($css);

$js_search = <<< JS
 function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}


// $(document).on('keyup','#js-search-input',delay(function(e) {
//   $('#storeproductsearch-product_name').val($(this).val())
//   $('#w0').yiiGridView("applyFilter");
// },500))


$(document).on('keyup','#js-search-input',function(e) {
  $('#storeproductsearch-product_name').val($(this).val())
  $('#w0').yiiGridView("applyFilter");
})

JS;
$this->registerJs($js_search);

?>
