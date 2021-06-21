<?php

use yii\helpers\Url;
use kartik\grid\GridView;
use app\components\AdminGrid;
use kartik\grid\ActionColumn;
use app\modules\shop\models\Order;
use app\modules\shop\models\OrderItem;
use jino5577\daterangepicker\DateRangePicker;
use app\modules\shop\widgets\OrderItemsWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
$checked = Yii::$app->request->get('OrderSearch')['status'] ? Yii::$app->request->get('OrderSearch')['status'] : [];
?>

<div class="row" id="dragdrop">
	<div class="col-md-3 col-sm-12">
		<a href="<?= Url::toRoute(['/shop/product/index']) ?>">
			<div class="card">
				<div class="card-body">
					<div class="stat-widget-two">
						<div class="media">
							<div class="media-body">
								<h2 class="mt-0 mb-1 text-primary"><?= $count ?></h2><span class="">Products</span>
							</div>
							<img class="ml-3" src="/images/logo-sm.svg" alt="">
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-3 col-sm-12">
		<a href="<?= Url::toRoute(['/shop/order/index']) ?>">
			<div class="card">
				<div class="card-body">
					<div class="stat-widget-two">
						<div class="media">
							<div class="media-body">
								<h2 class="mt-0 mb-1 text-primary"><?= $orders ?></h2><span class="">Total Orders</span>
							</div>
							<img class="ml-3" src="/images/logo-sm.svg" alt="">
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-3 col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="stat-widget-two">
					<div class="media">
						<div class="media-body">
							<h2 class="mt-0 mb-1 text-primary"><?= Yii::$app->formatter->asCurrency($summary) ?></h2><span class="">Total Summary</span>
						</div>
						<img class="ml-3" src="/images/logo-sm.svg" alt="">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-12">
		<a href="<?= Url::toRoute(['/shop/product/alert']) ?>">
			<div class="card">
				<div class="card-body">
					<div class="stat-widget-two">
						<div class="media">
							<div class="media-body">
								<h2 class="mt-0 mb-1 text-primary"><?= $alerts ?></h2><span class="">Total Alerts</span>
							</div>
							<img class="ml-3" src="/images/logo-sm.svg" alt="">
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
</div>
<div class="row mb-3">
	<?php foreach (Order::STATUSES as $key => $value) : ?>
		<div class="col-md-2">
			<div class="form-check">
				<input type="checkbox" class="form-check-input js-input-check" name="OrderSearch[status][]" <?= in_array($key, $checked) ? 'checked' : '' ?> value="<?= $key ?>" id="exampleCheck1<?= $key ?>">
				<label class="form-check-label" for="exampleCheck1<?= $key ?>"><?= $value ?></label>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<?= AdminGrid::widget([
	'title'                => 'Order',
	'pjax'                 => true,
	'dataProvider'         => $dataProvider,
	'filterModel'          => $searchModel,
	'panelHeadingTemplate' => '<div class="d-flex justify-content-between align-items-center flex-wrap">
    <div class="d-flex justify-content-start align-items-center">{gridTitle}</div>
    <div class="d-flex justify-content-end align-items-start">
    <a data-pjax="0" class="btn btn-primary mr-2" href="' . Url::toRoute(['/shop/order/export-order', 'OrderSearch' => Yii::$app->request->get('OrderSearch')]) . '">Export</a><a data-pjax="0" class="btn btn-primary" href="' .
		Url::toRoute(['/shop/order/index']) .
		'">Clear Filter</a>
</div>
</div>',
	'tableOptions'         => ['class' => 'text-normal '],
	'columns'              => [
		[
			'class'  => '\kartik\grid\ExpandRowColumn',
			'value'  => function ($model, $key, $index, $column) {
				return GridView::ROW_COLLAPSED;
			},
			'detail' => function ($model, $key, $index, $column) {
				return OrderItemsWidget::widget(['id' => $model->id]);
			},
		],
		[
			'attribute' => 'id',
			'label'     => 'Order ID',
		],
		[
			'label'     => 'Order No',
			'attribute' => 'no',
			'value'     => function ($model) {
				return $model->id + 10000;
			},
		],
		[
			'attribute' => 'customer_id',
			'label'     => 'Customer',
			'value'     => function ($model) {
				return $model->customer->name ?? '';
			},
		],
		//'transaction_id',
		[
			'attribute' => 'created_at',
			'filter'    => DateRangePicker::widget([
				'model'         => $searchModel,
				'attribute'     => 'created_at_range',
				'pluginOptions' => [
					'format'          => 'd-m-Y',
					'autoUpdateInput' => false,
				],
			]),
			'value'     => function ($model) {
				return Yii::$app->formatter->asDatetime($model->created_at);
			},
			'format'    => 'html',
		],
		'ship_price:currency',
		'ship_name',
		'tax:currency',
		[
			'label' => 'Discount',
			'value' => function ($model) {
				$coupon = $model->couponModel;
				
				$coup_price = 0;
				if ($coupon) {
					$sum = 0;
					foreach ($model->items as $item) {
						/**
						 * @var $item OrderItem
						 */
						$sum += $item->product_price * $item->qty;
					}
					$coup_price = $coupon->getCouponPrice($sum, $model->ship_price);
				}

				return $coup_price > 0 ? '-' . Yii::$app->formatter->asCurrency($coup_price) : 0;
			},
		],
		'total_cost:currency',
		[
			'contentOptions' => [
				'class' => 'text-center',
			],
			'label'          => 'Executor/Provider',
			'attribute'      => 'store_id',
			'value'          => function ($model) {
				if ($model->store_id == null) {
					return '<button data-id="' . $model->id . '" class="btn btn-link js-select-provider p-0">Executor not found</button>';
				} else {
					return $model->store->name;
				}
			},
			'filter'         => $exec,
			'format'         => 'raw',
		],
		[
			'contentOptions'      => [
				'class' => 'text-center',
			],
			'label'               => 'Status',
			'attribute'           => 'status',
			'value'               => function ($model) {
				return Order::STATUSES[$model->status];
			},
			'filter'              => Order::STATUSES,
			'filterType'          => GridView::FILTER_SELECT2,
			'filterWidgetOptions' => [
				'options'       => ['prompt' => '', 'multiple' => true],
				'pluginOptions' => [
					'allowClear' => true,
					//'tags'            => true,
					//'tokenSeparators' => [','],
				],
				'pluginEvents'  => [
					"change" => "function() {
					 let vals = $(this).val()
					 let inputs = $('.js-input-check')
					 inputs.each(function(){
					 if(vals.includes($(this).val())){
					 $(this).prop('checked',true)
					 }else{
					  $(this).prop('checked',false)
					 }
					 })
					 }",
				],
			],
		],
		[
			'label'  => 'Notes',
			'contentOptions'      => [
				'class' => 'td-note',
			],
			'value'  => function ($model) {
				return '<button data-id="' . $model->id . '" class="btn btn-link jt js-read-note">Notes</button>';
			},
			'format' => 'raw',
		],
		[
			'class'          => ActionColumn::class,
			'header'         => false,
			'width'          => false,
			'template'       => '<div class="actions">{view}<span class="ml-3"></span>{delete}</div>',
			'visibleButtons' => [
				'delete' => function ($model) {
					return !in_array($model->status, [0, 1, 2, 3, 7]);
				},
			],
		],
	],
]); ?>

<!-- Modal Note-->
<div class="modal fade" id="modalNote" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<textarea style="width: 100%" name="note" id="js-modal-note" rows="10"></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
				<button data-id="" type="button" class="btn btn-primary js-save-note">Save changes</button>
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
$(document).ready(function() {
  let elem = $('td:not([class])[data-col-seq=10]')
  elem.css('display','none')
})
$(document).on('pjax:end',function() {
  let elem = $('td:not([class])[data-col-seq=10]')
  elem.css('display','none')
})

$(document).on('click','.js-read-note',function() {
  let id = $(this).data('id')
  $.post({
  url:'/shop/order/read-note',
  data:{id:id},
  success:function(res) {
  $('#js-modal-note').val(res.note)
  $('#exampleModalLongTitle').text(res.number)
  $('.js-save-note').data('id',res.id)
  $('#modalNote').modal('show')
  }
  })
})

$(document).on('click','.js-save-note',function() {
  let id = $(this).data('id')
  let note = $('#js-modal-note').val()
  $.post({
  url:'/shop/order/save-note',
  data:{id:id,note:note},
  success:function() {
    $('#modalNote').modal('hide')
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

$(document).on('change','.js-input-check',function(){
let vals = $('#ordersearch-status').val()
let val = $(this).val()
if(vals.includes(val)){
vals.splice(vals.indexOf(val), 1);
}else{
vals.push(val)
}
$('#ordersearch-status').val(vals).trigger('change')
})
JS;
$this->registerJs($js)
?>
