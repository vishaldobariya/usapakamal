<?php

use app\components\AdminGrid;
use kartik\grid\ActionColumn;
use app\modules\shop\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<button class="btn btn-primary mb-4 js-connect">Connect</button>
<?= AdminGrid::widget([
	'title'        => 'Categories',
	'dataProvider' => $dataProvider,
	'filterModel'  => $searchModel,
	'columns'      => [
		AdminGrid::COLUMN_CHECKBOX,

		'name',
		[
			'attribute' => 'status',
			'value'     => function ($model) {
				return $model->status ? Category::STATUSES[$model->status] : '';
			},
			'filter'    => Category::STATUSES,
		],
		[
			'attribute' => 'parent_id',
			'label'     => 'Parent Category',
			'value'     => function ($model) {
				return $model->parent ? $model->parent->name : '';
			},
			'filter'    => $catParents,
		],
		//[
		//	'label' => 'Count Prods',
		//	'value' => function($model)
		//	{
		//		$prods =
		//		return count($prods);
		//	},
		//],

		[
			'class'    => ActionColumn::class,
			'header'   => 'Controls',
			'width'    => false,
			'template' => '<div class="actions">{update}<span class="ml-5"></span>{delete}</div>',
		],
	],
]); ?>
<?php $js = <<< JS
$(document).on('click','.js-connect',function() {
  let checked = [];

      $('.kv-row-checkbox').each(function() {
    	  if($(this).prop('checked') === true){
    	        	  checked.push($(this).val())
    	  }
    	})
    	$.post({
    	url:'/shop/category/connect',
    	data:{checked:checked},
    	success:function() {
    	  console.log('Connected')
    	  window.location.reload()
    	}
    	})
})
JS;
$this->registerJs($js); ?>
