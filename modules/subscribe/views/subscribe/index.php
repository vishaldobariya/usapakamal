<?php

use yii\helpers\Url;
use kartik\grid\ActionColumn;
use app\components\AdminGrid;
use app\modules\subscribe\models\Subscribe;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\subscribe\models\search\SubscribeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subscribes';
$this->params['breadcrumbs'][] = $this->title;
?>
<a class="btn btn-primary mb-3" href="<?= Url::toRoute(['/subscribe/subscribe/export']) ?>">Export All</a>
<?= AdminGrid::widget([
	'title'                => 'Subscribes',
	'panelHeadingTemplate' => '<div class="d-flex justify-content-between  flex-wrap align-items-center">
    <div class="d-flex justify-content-start align-items-center">{gridTitle}</div>
</div>',
	'dataProvider'         => $dataProvider,
	'filterModel'          => $searchModel,
	'columns'              => [
		['class' => 'yii\grid\SerialColumn'],
		
		'email:email',
		[
			'attribute' => 'status',
			'filter'    => Subscribe::STATUSES,
			'value'     => function($model)
			{
				return $model->getStatus();
			},
		],
		[
			'class'    => ActionColumn::class,
			'header'   => false,
			'width'    => false,
			'template' => '<div class="actions">{delete}</div>',
		
		],
	],
]); ?>
