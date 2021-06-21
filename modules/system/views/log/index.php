<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\components\AdminGrid;
use app\modules\system\models\Log;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\system\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= AdminGrid::widget([
	'title'        => 'Logs',
	'extraSearch'  => $this->render('_search', ['model' => $searchModel]),
	'dataProvider' => $dataProvider,
	'filterModel'  => $searchModel,
	'columns'      => [
		[
			'attribute' => 'id',
			'format'    => 'raw',
			'value'     => function(Log $model)
			{
				return Html::a($model->logName, ['view', 'id' => $model->id], ['data-pjax' => 0]);
			},
		],
		[
			'attribute' => 'level',
			'filter'    => Log::$levels,
			'value'     => 'levelName',
		],
		'category',
		'log_time:datetime',
		[
			'attribute'      => 'message',
			'contentOptions' => [
				'class' => 'text-wrap',
			],
			'value'          => function($model)
			{
				return ArrayHelper::getValue(explode(PHP_EOL, $model->message), 0);
			},
		],
	],
]); ?>
