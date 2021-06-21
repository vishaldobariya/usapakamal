<?php

use app\components\AdminGrid;
use kartik\grid\ActionColumn;
use kartik\grid\BooleanColumn;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\user\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-index">

	<?= AdminGrid::widget([
		'title'        => 'Users',
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'tableOptions' => ['class' => 'text-normal'],
		'columns'      => [
			'email:email',
			'first_name',
			'last_name',
			'role',
			[
				'class'     => BooleanColumn::class,
				'attribute' => 'blocked',
			],
			[
				'class'     => BooleanColumn::class,
				'attribute' => 'confirmed',
			],
			'last_login_at:datetime',
			[
				'class'    => ActionColumn::class,
				'header'   => false,
				'width'    => false,
				'template' => '<div class="actions">{switch}<span class="ml-3"></span>{update}<span class="ml-3"></span>{delete}</div>',

				'buttons' => [
					'switch' => function ($url, $model) {
						$htmlOptions['class'] = 'tabledit-edit-button btn btn-primary';
						$htmlOptions['data-pjax'] = 0;
						$htmlOptions['title'] = 'Switch Identity to ' . $model->name;

						return Html::a(
							'Login',
							['/user/default/switch', 'id' => $model->id],
							$htmlOptions
						);
					},

				],
			],
		],
	]); ?>

</div>
