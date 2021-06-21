<?php

use app\components\AdminGrid;
use kartik\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contacts';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= AdminGrid::widget([
	'title'        => 'Contacts',
	'dataProvider' => $dataProvider,
	'filterModel'  => $searchModel,
	'tableOptions'         => ['class' => 'text-normal'],
	'columns'      => [
		['class' => 'yii\grid\SerialColumn'],

		'first_name',
		'last_name',
		'email:email',
		'phone',
		[
			'attribute' => 'text',
			'value'     => function ($model) {
				return '<button class="btn btn-outline-primary js-read" data-id="' . $model->id . '">Read</button>';
			},
			'format'    => 'raw',
		],

		[
			'class'    => ActionColumn::class,
			'header'   => false,
			'width'    => false,
			'template' => '<div class="actions">{delete}</div>',
		],
	],
]); ?>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="js-content">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<?php $js = <<< JS
$(document).on('click','.js-read',function() {
  $.post({
  url:'/shop/contact/read',
  data:{id:$(this).data('id')},
  success:function(res) {
    let modal = $('#exampleModalCenter')
    $('#js-content').text(res)
    modal.modal('show')
  }
  })
})
JS;
$this->registerJs($js) ?>