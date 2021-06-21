<?php

use app\components\AdminGrid;
use app\modules\shop\models\Coupon;
use kartik\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">
	<?= AdminGrid::widget([
		'title'        => 'Customers',
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'panelHeadingTemplate' => '
<div class="d-flex justify-content-between  flex-wrap align-items-center">
    <div class="d-flex justify-content-start align-items-center">{gridTitle}</div>
</div>',
		'columns'      => [
			'first_name',
			'last_name',
			'email',
			[
				'class'    => ActionColumn::class,
				'header'   => 'Controls',
				'width'    => false,
				'template' => '<div class="actions">{view}</div>',
			],
		],
	]); ?>
</div>