<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\select2\Select2;
use app\modules\admin\assets\DashboardAsset;

DashboardAsset::register($this);

$this->title = 'Dashboard';

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
<div class="row">
  <div class="col-xl-8 col-xxl-7 col-lg-8">
    <div class="card h-100">
      <div class="card-header">
        <?= Select2::widget([
	        'name'          => 'kv-type-01',
	        'options'       => [
		        'placeholder' => 'Select a product ...',
	        ],
	        'pluginOptions' => [
		        'allowClear'         => true,
		        'minimumInputLength' => 3,
		        'language'           => [
			        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
		        ],
		        'ajax'               => [
			        'url'      => '/admin/dashboard/find-products',
			        'dataType' => 'json',
			        'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
		        ],
	        ],
        ]); ?>
      </div>
      <div class="card-body" id="card-body">
        <canvas id="monthly-orders-chart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-xl-4 col-xxl-5 col-lg-4">
    <div class="card h-100">
      <div class="card-header">
        <div class="card-action"><a href="javascript:void(0)" data-action="collapse"><i class="ti-plus"></i></a> <a href="javascript:void(0)" data-action="expand"><i class="icon-size-fullscreen"></i></a>
          <a href="javascript:void(0)" data-action="close"><i class="ti-close"></i>
          </a><a href="javascript:void(0)" data-action="reload"><i class="icon-reload"></i></a>
        </div>
        <h4 class="card-title">Most Selling Items</h4>
      </div>
      <div class="card-body">
        <canvas id="most-selling-items"></canvas>
      </div>
    </div>
  </div>
</div>
<?php $js = <<< JS
$(document).ready(function() {
  renderChart(["2001", "2002", "2003", "2004", "2005", "2006", "2007", "2008", "2009", "2010", "2011", "2012", "2013", "2014", "2015", "2016", "2017", "2018", "2019", "2020"],[0, 29, 84, 96, 37, 70, 45, 63, 47, 99, 23, 32, 59, 87, 57, 34, 74, 39, 71, 44])
})
$(document).on('change','#w0',function() {
  let val = $(this).val();
  $.post({
  url:'/admin/dashboard/get-prices-by-product',
  data:{val:val},
  success:function(res) {
  let card_body = $('#card-body')
    card_body.html('')
    card_body.html('<canvas id="monthly-orders-chart"></canvas>')
     renderChart(res.labels,res.data)
  }
  })
})
JS;
$this->registerJs($js)
?>
