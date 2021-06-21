<?php

use jino5577\daterangepicker\DateRangePicker;
use kartik\grid\GridView;
use app\components\AdminGrid;
use kartik\grid\ActionColumn;
use app\modules\shop\models\Order;
use app\modules\shop\widgets\OrderItemsWidget;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
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

<?= AdminGrid::widget([
  'title'        => 'Order',
  'dataProvider' => $dataProvider,
  'filterModel'  => $searchModel,
  'createButton' => '<span></span>',
  'tableOptions' => ['class' => 'text-normal table-order'],
  'pjax'         => false,
  'columns'      => [
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
      'label'     => 'Order No',
      'attribute' => 'id',
      'value'     => function ($model) {
        return $model->id + 10000;
      },
    ],
    [
      'attribute' => 'customer_id',
      'label'     => 'Customer',
      'value'     => function ($model) {
        return $model->customer->name;
      },
    ],

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

    'total_cost:currency',
    'transaction_id',
    [
      'attribute' => 'status',
      'value'     => function ($model) {
        return Order::STATUSES[$model->status];
      },
      'filter'    => Order::STATUSES,
    ],
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
      'class'  => '\kartik\grid\ExpandRowColumn',
      'value'  => function ($model, $key, $index, $column) {
        return GridView::ROW_COLLAPSED;
      },
      'detail' => function ($model, $key, $index, $column) {
        return OrderItemsWidget::widget(['id' => $model->id]);
      },
    ],
    [
      'class'    => ActionColumn::class,
      'header'   => false,
      'width'    => false,
      'template' => '<div class="actions">{view}<span class="ml-3"></span>{delete}</div>',
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button data-id="" type="button" class="btn btn-primary js-push-provider">Push to Provider</button>
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
JS;
$this->registerJs($js)
?>