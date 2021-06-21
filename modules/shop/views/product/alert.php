<?php

use yii\helpers\Url;
use kartik\editable\Editable;
use app\components\AdminGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Alert Products';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= AdminGrid::widget([
	'title'                => 'Alert Products',
	'dataProvider'         => $dataProvider,
	'filterModel'          => $searchModel,
	'panelHeadingTemplate' => '<div class="d-flex justify-content-between align-items-center">
    <div class="d-flex justify-content-start align-items-center">{gridTitle}</div>
</div>',
	'tableOptions'         => ['class' => 'text-normal'],
	'columns'              => [
		[
			'label'   => 'Image',
			'visible' => Yii::$app->session->has('hide_image') ? false : true,
			'value'   => function($model)
			{
				return '<img width="" height="auto" class="table-img img-responsive" src="' . $model->thumb . '">';
			},
			'format'  => 'raw',
		],
		[
			'attribute' => 'name',
			'filter'    => false,
			'format'    => 'raw',
			'value'     => function($model)
			{
				return '<a class="btn-link" data-pjax="0" href="'.Url::toRoute(['/shop/product/update','id' => $model->id]).'">'.$model->name.'</a>';
			},
		],
		[
			'attribute' => 'price',
			'label'     => 'Website Price',
			'format'    => 'raw',
			'value'     => function($model)
			{
				$html = '';
				if(!empty($model->storeProducts)) {
					$percent = ($model->getPrice() - $model->storeProducts[0]->price) / $model->storeProducts[0]->price;
					$display = $percent * 100 > 30 ? 'display:none' : 'display:block';
					$html .= '<progress id="progress" style="width:100%" max="' . $model->getPrice() . '" title="' . $model->storeProducts[0]->store->name . '" value="' .
						$model->storeProducts[0]->price . '"></progress>
		<div class="help-block invalid-feedback" style="' . $display . '">Attention! Your price is lower or equal than the supplier\'s price + 30%</div>';
				}
				
				return Editable::widget([
						'name'         => 'price',
						'asPopover'    => false,
						'value'        => $model->price,
						'header'       => 'Price',
						'size'         => 'md',
						'formOptions'  => [
							'action' => Url::toRoute(['/shop/product/change-price', 'id' => $model->id]),
						],
						'pluginEvents' => [
							"editableSuccess"      => "function(event, val, form, data) {
							       $.pjax.reload({container: '#w0-pjax', timeout: false})
							 }",
						],
						'options'      => ['class' => 'form-control', 'placeholder' => 'Enter a price...'],
					]) . $html;
			},
		],
		[
			'label'  => 'Provider Price',
			'filter' => false,
			'value'  => function($model)
			{
				if(!empty($model->storeProducts)) {
					return Yii::$app->formatter->asCurrency($model->storeProducts[0]->price);
				} else {
					return 'No provider yet';
				}
				
			},
		],
		[
			'label' => 'Provider',
			'value' => function($model)
			{
				return $model->storeProducts[0]->store->name;
			},
		],
	],
]); ?>


