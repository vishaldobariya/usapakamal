<?php

use app\components\AdminGrid;
use kartik\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\BrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Brands';
$this->params['breadcrumbs'][] = $this->title;
?>
<button class="btn btn-primary mb-5 js-connect">Connect</button>
<div class="brand-index">
	<?= AdminGrid::widget([
		'title'        => 'Brands',
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			AdminGrid::COLUMN_CHECKBOX,

			'name',
			'position',
			[
				'label' => 'Count Prods',
				'value' => function ($model) {
					return count($model->products);
				},
			],

			[
				'class'    => ActionColumn::class,
				'header'   => 'Controls',
				'width'    => false,
				'template' => '<div class="actions">{update}<span class="ml-5"></span>{delete}</div>',
			],
		],
	]); ?>
</div>

<?php $js = <<< JS
$(document).on('click','.js-connect',function() {
  let checked = [];

      $('.kv-row-checkbox').each(function() {
    	  if($(this).prop('checked') === true){
    	        	  checked.push($(this).val())
    	  }
    	})
    	$.post({
    	url:'/shop/brand/connect',
    	data:{checked:checked},
    	success:function() {
    	  console.log('Connected')
    	  window.location.reload()
    	}
    	})
})
JS;
$this->registerJs($js); ?>