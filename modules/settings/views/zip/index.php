<?php

use app\components\AdminGrid;
use kartik\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\settings\models\search\ZipSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Zips';
$this->params['breadcrumbs'][] = $this->title;
$state = Yii::$app->request->get('ZipSearch')['state'] ?? '';
?>

<?= AdminGrid::widget([
  'title'                => 'Zip',
  'panelHeadingTemplate' => '<div class="d-flex justify-content-between flex-wrap align-items-center">
    <div class="d-flex justify-content-start align-items-center">{createButton}{gridTitle}</div>
    <div class="d-flex justify-content-end align-items-start"><button class="btn  btn-outline-primary  mr-2" data-state="' . $state . '" id="js-enable-zip">Enable All Zips ' . $state . ' </button>
    <button class="btn btn-outline-primary mb-3" data-state="' . $state . '" id="js-disable-zip">Disable All Zips ' . $state . ' </button>
</div>
</div>',
  'dataProvider'         => $dataProvider,
  'filterModel'          => $searchModel,
  'columns'              => [
    'zipcode',
    [
      'attribute' => 'state',
      'filter'    => $states,
    ],
    'active:boolean',

    [
      'class'    => ActionColumn::class,
      'header'   => 'Controls',
      'width'    => false,
      'template' => '<div class="actions">{update}<span class="ml-5"></span>{delete}</div>',
    ],
  ],
]); ?>

<?php $js = <<< JS
$(document).on('click','#js-enable-zip',function() {
  let state = $(this).data('state')
  $.post({
  url:'/settings/zip/enable-zip',
  data:{state:state},
  success:function() {
    $.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})

$(document).on('click','#js-disable-zip',function() {
  let state = $(this).data('state')
  $.post({
  url:'/settings/zip/disable-zip',
  data:{state:state},
  success:function() {
    $.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})
JS;
$this->registerJs($js) ?>
