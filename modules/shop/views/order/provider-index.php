<?php

use kartik\grid\GridView;
use app\components\AdminGrid;
use kartik\grid\ActionColumn;
use app\modules\shop\models\Order;
use app\modules\shop\widgets\ProviderItemsWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= AdminGrid::widget([
	'title'        => 'Order',
	'dataProvider' => $dataProvider,
	'filterModel'  => $searchModel,
	'createButton' => '<span></span>',
	'tableOptions' => ['class' => 'text-normal'],
	'columns'      => [
		[
			'class'  => '\kartik\grid\ExpandRowColumn',
			'value'  => function($model, $key, $index, $column)
			{
				return GridView::ROW_COLLAPSED;
			},
			'detail' => function($model, $key, $index, $column)
			{
				return ProviderItemsWidget::widget(['id' => $model->id]);
			},
		],
		[
			'label' => '#',
			'value' => function($model)
			{
				return $model->id + 10000;
			},
		],
		[
			'label' => 'Customer',
			'value' => function($model)
			{
				return $model->customer->user->name ?? '';
			},
		],
		[
			'label' => 'Customer Address',
			'value' => function($model)
			{
				return $model->customer->fullAddress;
			},
		],
		[
			'label'  => 'Customer Contact',
			'value'  => function($model)
			{
				return '<a href="mailto:' . $model->customer->email . '">' . $model->customer->email . '</a>';
			},
			'format' => 'raw',
		],
		'created_at:dateTime',
		[
			'label' => 'Total Items',
			'value' => function($model)
			{
				/**
				 * @var $model Order
				 */
				return Yii::$app->formatter->asCurrency($model->total_provider_cost);
			},
		],
		'ship_name',
		[
			'label' => 'Tax',
			'value' => function($model)
			{
				/**
				 * @var $model Order
				 */
				$i = $model->hasEngraving();
				
				$eng = $i > 0 ? $i * Yii::$app->settings->front_engraving : 0;
				$tax = ($model->ship_price + $model->total_provider_cost + $eng) * ((float)\Yii::$app->settings->tax / 100);
				
				return Yii::$app->formatter->asCurrency($tax);
			},
		],
		[
			'label' => 'Total Cost',
			'value' => function($model)
			{
				/**
				 * @var $model Order
				 */
				$i = $model->hasEngraving();
				
				$eng = $i > 0 ? $i * Yii::$app->settings->front_engraving : 0;
				$tax = ($model->ship_price + $model->total_provider_cost + $eng) * ((float)\Yii::$app->settings->tax / 100);
				$total = $model->ship_price + $model->total_provider_cost + $eng + $tax;
				
				return Yii::$app->formatter->asCurrency($total);
			},
		],
		[
			'label' => 'Status',
			'value' => function($model)
			{
				return Order::STATUSES[$model->status];
			},
		],
		[
			'contentOptions' => [
				'class' => 'text-center',
			],
			'visible'        => Yii::$app->session->has('role'),
			'label'          => 'Select other provider',
			'value'          => function($model)
			{
				return '<button data-id="' . $model->id . '" class="btn btn-link js-select-provider p-0">Push to other provider</button>';
				
			},
			'filter'         => false,
			'format'         => 'raw',
		],
		[
			'class'          => ActionColumn::class,
			'header'         => false,
			'width'          => false,
			'template'       => '<div class="actions">{view}<span class="ml-2"></span> {accept}<span class="ml-2"></span>{refuse}<span class="ml-2"></span>{shipped}<span class="ml-2"></span>{delivered}<span class="ml-2"></span>{problematic}<span class="ml-2"></span>{done}</div>',
			'visibleButtons' => [
				'refuse'      => function($model)
				{
					/**
					 * @var $model Order
					 */
					return $model->status == 0;
				},
				'problematic' => function($model)
				{
					/**
					 * @var $model Order
					 */
					return $model->status == 0;
				},
			],
			'buttons'        => [
				'accept'      => function($url, $model)
				{
					return '<a title="accept" data-toggle="tooltip" data-placement="top" id="js-modal-ship-date" data-id="' . $model->id . '" href="#"><i class="fas fa-check-square"></i></a>';
				},
				'refuse'      => function($url, $model)
				{
					return '<a title="refuse" data-toggle="tooltip" data-placement="top" class="js-refuse" data-id="' . $model->id . '" href="#"><i class="fas fa-window-close"></i></a>';
				},
				'done'        => function($url, $model)
				{
					return '<a title="done" data-toggle="tooltip" data-placement="top" class="js-done" data-id="' . $model->id . '" href="#"><i class="fas fa-clipboard-check"></i></a>';
				},
				'problematic' => function($url, $model)
				{
					return '<a title="problematic" data-toggle="tooltip" data-placement="top" class="js-problematic" data-id="' . $model->id . '" href="#"><i class="fa fa-exclamation-circle"></i></a>';
				},
				'shipped'     => function($url, $model)
				{
					return '<a title="shipped" data-toggle="tooltip" data-placement="top" class="js-shipped" data-id="' . $model->id . '" href="#"><i class="fa fa-cart-arrow-down"></i></a>';
				},
				'delivered'   => function($url, $model)
				{
					return '<a title="delivered" data-toggle="tooltip" data-placement="top" class="js-delivered" data-id="' . $model->id . '" href="#"><i class="fa fa-truck"></i></a>';
				},
			],
		],
	],
]); ?>

<!-- Modal Ship Date -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Enter Ship Date</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
    <label for="exampleFormControlInput1">Ship Date</label>
    <input type="date" class="form-control" id="js-ship-date" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" placeholder="name@example.com">
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" data-id="" class="btn btn-primary js-accept">Accept Order </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Error -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="js-title">Oops! Something went wrong...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="js-modal-body">
      <div class="alert alert-success" id="js-text" role="alert">
  This is a success alert—check it out!
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Error -->
<div class="modal fade" id="engravingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Engravings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="content-engraving">
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Select Provider -->
<div class="modal fade" id="providerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="providerTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="exampleFormControlSelect1">Select Provider</label>
					<?php unset($exec[0]) ?>
					<select class="form-control" id="exampleFormControlSelect1">
						<?php foreach ($exec as $key => $ex) : ?>
							<option value="<?= $key ?>"><?= $ex ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
				<button data-id="" type="button" class="btn btn-primary js-push-provider">Push to Provider</button>
			</div>
		</div>
	</div>
</div>

<?php $js = <<< JS
$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$(document).on('pjax:end',function() {
   $('[data-toggle="tooltip"]').tooltip()
})


$(document).on('click','#js-modal-ship-date',function() {
$('.js-accept').data('id',$(this).data('id'))
$('#exampleModalCenter').modal('show')
})

$(document).on('click','.js-accept',function(e) {
  e.preventDefault()
  $('#exampleModalCenter').modal('hide')
  let id = $(this).data('id')
  let shipDate = $('#js-ship-date').val()
  $('#preloader').css('display','block')
   $.post({
  url:'/shop/order/order-accept',
  data:{id:id,shipDate:shipDate},
  success:function(res){
    $('#preloader').css('display','none')
    if (res.code === '400' || res.code === '401'){
      $('#js-title').text('Oops! Something went wrong...')
      $('#js-modal-body').html(`<div class="alert alert-danger" id="js-text" role="alert">
  `+res.message+`
</div>`)
    
    } else{
      	$('#js-title').text('Success!!!')
      $('#js-modal-body').html(`<div class="alert alert-success" id="js-text" role="alert">
  `+res.message+`
</div>`)
          $.pjax.reload({container: "#w0-pjax", timeout: false})

    }
    $('#errorModal').modal('show')
  }
  })
})

$(document).on('click','.js-done',function(e) {
  e.preventDefault()
  let id = $(this).data('id')
  $.post({
  url:'/shop/order/order-done',
  data:{id:id},
  success:function(){
    $.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})

$(document).on('click','.js-refuse',function(e) {
  e.preventDefault()
  let id = $(this).data('id')
  $.post({
  url:'/shop/order/order-refuse',
  data:{id:id},
  success:function(){
    $.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})

$(document).on('click','.js-shipped',function(e) {
  e.preventDefault()
  let id = $(this).data('id')
  $.post({
  url:'/shop/order/order-shipped',
  data:{id:id},
  success:function(){
    $.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})
$(document).on('click','.js-delivered',function(e) {
  e.preventDefault()
  let id = $(this).data('id')
  $.post({
  url:'/shop/order/order-delivered',
  data:{id:id},
  success:function(){
    $.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})
$(document).on('click','.js-problematic',function(e) {
  e.preventDefault()
  let id = $(this).data('id')
  $.post({
  url:'/shop/order/order-problematic',
  data:{id:id},
  success:function(){
    $.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})

$(document).on('click','.js-select-provider',function() {
  let id = $(this).data('id')
  let title = Number(id) +1000
  $('#providerTitle').text('#'+title)
  $('.js-push-provider').data('id',id)
  $('#providerModal').modal('show')
})

$(document).on('click','.js-push-provider',function() {
  let id = $(this).data('id')
  let val = $('#exampleFormControlSelect1').val()
  $.post({
  url:'/shop/order/push-provider',
  data:{id:id,val:val},
  success:function(){
      $('#providerModal').modal('hide')
      $.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})
JS;
$this->registerJs($js);
?>
