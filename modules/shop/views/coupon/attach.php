<?php

use kartik\editable\Editable;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
use app\components\AdminGrid;
use kartik\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\CouponUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coupon Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= AdminGrid::widget([
	'dataProvider'         => $dataProvider,
	'filterModel'          => $searchModel,
	'panelHeadingTemplate' => '<div class="d-flex justify-content-between  flex-wrap align-items-center">
    <div class="d-flex justify-content-start align-items-center"><button class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Attach Users</button></div>
</div>',
	'columns'              => [
		['class' => 'yii\grid\SerialColumn'],
		'email:email',
		[
			'attribute' => 'count',
			'format'    => 'raw',
			'value'     => function ($model) {
				return Editable::widget([
					'name'         => 'count',
					'asPopover'    => false,
					'value'        => $model->count,
					'header'       => 'Price',
					'size'         => 'md',
					'formOptions'  => [
						'action' => Url::toRoute(['/shop/coupon/change-count', 'id' => $model->id]),
					],
					'pluginEvents' => [
						"editableSuccess"      => "function(event, val, form, data) {
							       $.pjax.reload({container: '#w0-pjax', timeout: false})
							 }",
					],
					'options'      => ['class' => 'form-control', 'placeholder' => 'Enter a price...'],
				]);
			},
		],
		[
			'class'    => ActionColumn::class,
			'header'   => 'Controls',
			'width'    => false,
			'template' => '<div class="actions">{delete}</div>',
			'buttons'  => [
				'delete' => function ($url, $model) {
					return '<a href="' . Url::toRoute([
						'/shop/coupon/delete-attach',
						'id' => $model->id,
					]) . '"><svg class="svg-inline--fa fa-trash-alt fa-w-14" aria-hidden="true" data-prefix="fas" data-icon="trash-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M0 84V56c0-13.3 10.7-24 24-24h112l9.4-18.7c4-8.2 12.3-13.3 21.4-13.3h114.3c9.1 0 17.4 5.1 21.5 13.3L312 32h112c13.3 0 24 10.7 24 24v28c0 6.6-5.4 12-12 12H12C5.4 96 0 90.6 0 84zm416 56v324c0 26.5-21.5 48-48 48H80c-26.5 0-48-21.5-48-48V140c0-6.6 5.4-12 12-12h360c6.6 0 12 5.4 12 12zm-272 68c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208zm96 0c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208zm96 0c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208z"></path></svg></a>';
				},
			],
		],
	],
]); ?>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Attach Users</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?php $form = ActiveForm::begin() ?>
			<div class="modal-body">
				<?= $form->field($model, 'count')->input('number', ['min' => 0, 'step' => 1])->label('Number of Uses') ?>
				<?= $form->field($model, 'email')->widget(Select2::class, [
					'data'          => $users,
					'options'       => [
						'placeholder' => 'Select a users...',
						'multiple'    => true,
					],
					'pluginOptions' => [
						'tags'               => true,
						'tokenSeparators'    => [','],
						'maximumInputLength' => 20,
					],
				])->label('Users') ?>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
			<?php ActiveForm::end() ?>
		</div>
	</div>
</div>