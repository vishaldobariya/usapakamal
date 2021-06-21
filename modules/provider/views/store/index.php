<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\components\AdminGrid;
use kartik\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\provider\models\search\StoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Providers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-index">

<?= AdminGrid::widget([
	'title'        => 'Invite Provider',
	'dataProvider' => $dataProvider,
	'filterModel'  => $searchModel,
	'columns'      => [
		AdminGrid::COLUMN_CHECKBOX,
		'email',
		[
			'label' => 'Provider Name',
			'value' => function($model)
			{
				return $model->store->name ?? '';
			},
		],
		
		[
			'class'    => ActionColumn::class,
			'header'   => false,
			'width'    => false,
			'template' => '<div class="actions">{switch}<span class="ml-3"></span>{delete}</div>',
			
			'buttons' => [
				'switch' => function($url, $model)
				{
					$htmlOptions['class'] = 'tabledit-edit-button btn btn-link';
					$htmlOptions['data-pjax'] = 0;
					$htmlOptions['title'] = 'Switch Identity to ' . $model->name;
					
					return Html::a('Login', ['/user/default/switch', 'id' => $model->id],
						$htmlOptions);
				},
				'delete' => function($url, $model)
				{
					return '<a class="btn btn-primary" href="' . Url::toRoute([
							'/provider/store/delete',
							'id' => $model->id,
						]) . '" title="Delete" aria-label="Delete" data-pjax="0" data-method="post" data-confirm="Are you sure to delete this item?">Delete</a>';
				},
			
			],
		],
	],
]); ?>

</div>
