<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\system\models\Log */

$this->title = $model->logName;
$this->params['breadcrumbs'][] = ['label' => 'Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="log-view">
	
	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'id',
			'levelName',
			'category',
			'log_time:datetime',
			'prefix:ntext',
			'message:ntext',
		],
	]) ?>

</div>
