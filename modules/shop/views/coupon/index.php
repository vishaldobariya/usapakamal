<?php

use yii\helpers\Url;
use kartik\grid\ActionColumn;
use app\components\AdminGrid;
use kartik\grid\CheckboxColumn;
use app\modules\shop\models\Coupon;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coupons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">
<?= AdminGrid::widget([
	'title'                => 'Coupons',
	'panelHeadingTemplate' => '
<div class="d-flex justify-content-between align-items-center flex-wrap">
    <div class="d-flex justify-content-start align-items-center">{createButton}{gridTitle}</div>
    <div class="d-flex justify-content-end align-items-end"><button class="btn btn-primary js-delete-coupon">Delete<span id="js-items-count"></span></button></div>
</div>',
	'dataProvider'         => $dataProvider,
	'filterModel'          => $searchModel,
	'columns'              => [
		'name',
		[
			'attribute' => 'value',
			'value'     => function(Coupon $model)
			{
				return Yii::$app->formatter->asDecimal($model->value) . ($model->is_usd ? '$' : '%');
			},
		],
		[
			'attribute' => 'status',
			'value'     => function($model)
			{
				return Coupon::STATUSES[$model->status];
			},
			'filter'    => Coupon::STATUSES,
		],
		[
			'header' => '<input type="checkbox"  class="select-all" name="select_all" value="1">',
			'class'  => CheckboxColumn::class,
		],
		[
			'class'    => ActionColumn::class,
			'header'   => 'Controls',
			'width'    => false,
			'template' => '<div class="actions">{attach}<span class="ml-5"></span>{update}<span class="ml-5"></span>{delete}</div>',
			'buttons'  => [
				'attach' => function($url, $model)
				{
					return '<a data-pjax="0" href="' . Url::toRoute(['/shop/coupon/attach', 'id' => $model->id]) . '"><i class="fas fa-users"></i></a>';
				},
			],
		],
	],
]); ?>
</div>
<?php $js = <<< JS
let values = []
let count = 0
$('.kv-row-checkbox').each(function() {
  count++
  if ($(this).prop('checked')===true){
    values.push($(this).val())
  }
    	})
    	
    	
   $(document).on('change','.select-all',function() {
    
    if ($(this).prop('checked') === true){
    	$('.kv-row-checkbox').each(function() {
    	  $(this).prop('checked',true)
    	  values.push($(this).val())
    	})
    
            $('#js-items-count').text(' '+ values.length+' items')
          

    } else{
      $('.kv-row-checkbox').each(function() {
    	  $(this).prop('checked',false)
    	   let idx = values.findIndex((elem)=> elem === $(this).val())
           values.splice(idx, 1)
    	})
    	
       $('#js-items-count').text('')
    }
    
        
        })
    

    $(document).on('change','.kv-row-checkbox',function() {
      const val = $(this).val()
 	if ($(this).prop('checked') === true){
 	  values.push(val)
    } else{
      let idx = values.findIndex((elem)=> elem === val)
      values.splice(idx, 1)
    }
    if (count === values.length){
      $('.select-all').prop('checked',true)
    } else{
      $('.select-all').prop('checked',false)
    }
    
    if( values.length === 0){
      $('#js-items-count').text('')
    }else{
          $('#js-items-count').text(' '+ values.length+' items')

    }
        })

$(document).on('click','.js-delete-coupon',function() {
  if (values.length > 0){
    $.post({
    url:'/shop/coupon/delete-all',
    data:{ids:values},
    success:function() {
             $.pjax.reload({container: "#w0-pjax", timeout: false})
    }
    })
  }
})

JS;
$this->registerJs($js) ?>
