<?php

use app\modules\shop\models\OrderItem;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\components\AdminGrid;
use kartik\grid\ActionColumn;
use app\modules\shop\models\Order;
use app\modules\shop\widgets\OrderItemsWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;

$checked = isset(Yii::$app->request->get('OrderSearch')['status']) ? Yii::$app->request->get('OrderSearch')['status'] : [];
$statuses = Order::STATUSES;
unset($statuses[6])
?>

<?php if(Yii::$app->controller->action->id != 'user-saved-index') : ?>
	<div class="col-sm-6 col-md-4 px-0 form-group input-select ">
		<select name="" id="" class="js-input-select form-control w-100">
			<option value="">All</option>
			<?php foreach($statuses as $key => $value) : ?>
				<option value="<?= $key ?>"><?= $value ?></option>
			<?php endforeach; ?>
		</select>
	</div>
<?php endif; ?>
<?= AdminGrid::widget([
	'title'               => 'Order',
	'dataProvider'        => $dataProvider,
	'filterModel'         => $searchModel,
	'createButton'        => '<span></span>',
	'tableOptions'        => ['class' => 'text-normal  table-order'],
	'panelFooterTemplate' => '
	<div class="d-flex justify-content-between  flex-wrap align-items-center">
	<a href="/admin/dashboard/welcome" class="btn btn-outline-primary" >My Account</a>

		<div>{pager}</div>
	</div>',
	
	'columns' => [
		[
			'filter' => false,
			'class'  => '\kartik\grid\ExpandRowColumn',
			'value'  => function($model, $key, $index, $column)
			{
				return GridView::ROW_COLLAPSED;
			},
			'detail' => function($model, $key, $index, $column)
			{
				return OrderItemsWidget::widget(['id' => $model->id]);
			},
		],
		[
			'filter'    => false,
			'label'     => 'Order No',
			'attribute' => 'id',
			'value'     => function($model)
			{
				return $model->id + 10000;
			},
		],
		[
			'filter'    => false,
			'attribute' => 'customer_id',
			'label'     => 'Customer',
			'value'     => function($model)
			{
				return $model->customer->name ?? '';
			},
		],
		//'transaction_id',
		[
			'filter'    => false,
			'attribute' => 'created_at',
			'format'    => 'dateTime',
		],
		[
			'filter'    => false,
			'attribute' => 'ship_price',
			'format'    => 'currency',
		],
		[
			'filter'    => false,
			'attribute' => 'ship_name',
		],
		[
			'filter'    => false,
			'attribute' => 'tax',
			'format'    => 'currency',
		],
		[
			'label' => 'Discount',
			'value' => function($model)
			{
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
		[
			'filter'    => false,
			'attribute' => 'total_cost',
			'format'    => 'currency',
		],
		[
			'label'     => 'Status',
			'attribute' => 'status',
			'filter'    => false,
			'value'     => function($model)
			{
				return Order::STATUSES[$model->status];
			},
			
			//'filter' => Yii::$app->user->identity->role == 'admin' ? false : Order::STATUSES,
			//		'filterType'          => GridView::FILTER_SELECT2,
			//		'filterWidgetOptions' => [
			//			'options'       => ['prompt' => '', 'multiple' => true],
			//			'pluginOptions' => [
			//				'allowClear' => true,
			//				//'tags'            => true,
			//				//'tokenSeparators' => [','],
			//			],
			//
			//			'pluginEvents'  => [
			//				"change" => "function() {
			//
			//// 					let val = $(this).val();
			////   $('.js-input-select').val(val).trigger('change');
			//				 }",
			//			],
			//		],
		],
		[
			'label'  => 'Notes',
			'contentOptions'      => [
				'class' => 'td-note',
			],
			'filter' => false,
			'value'  => function($model)
			{
				return '<button data-id="' . $model->id . '" class="btn btn-link js-read-note">Notes</button>';
			},
			'format' => 'raw',
		],
		[
			'class'          => ActionColumn::class,
			'header'         => false,
			'width'          => false,
			'template'       => '<div class="actions">{pay}<span class="ml-3"></span>{view}<span class="ml-3"></span>{delete}</div>',
			'visibleButtons' => [
				'pay'    => function($model)
				{
					return $model->status == 6 ? true : false;
				},
				'delete' => function($model)
				{
					return $model->status == 6 ? true : false;
					
				},
			],
			'buttons'        => [
				'pay' => function($url, $model)
				{
					return '<a title="pay now" data-pjax="0" href="' . Url::toRoute(['/shop/order/continue', 'id' => $model->id]) . '"><i class="fa fa-shopping-cart" aria-hidden="true"></i>
</a>';
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
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button data-id="" type="button" class="btn btn-primary js-save-note">Save changes</button>
			</div>
		</div>
	</div>
</div>


<?php $js = <<< JS
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

$('.js-input-select').on("change", function () {
      let val = $(this).val();
	  $('#ordersearch-status').val(val).change();
})


JS;
$this->registerJs($js)
?>
