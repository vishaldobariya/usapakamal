<?php

use kartik\editable\Editable;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\components\AdminGrid;
use kartik\grid\ActionColumn;
use kartik\grid\CheckboxColumn;

$get = Yii::$app->request->get();
$active = 'all';
if (isset($get['StoreProductSearch']['connected'])) {
  $active = $get['StoreProductSearch']['connected'] == 1 ? 'connected' : 'not connected';
}
$ids = ArrayHelper::getColumn($dataProvider->models, 'id', 'id');

$main_check = '';
$i = 0;

if (Yii::$app->session->has('items')) {
  foreach ($ids as $mod_id) {
    if (in_array($mod_id, Yii::$app->session->get('items'))) {
      $i++;
    }
  }
  if ($i == Yii::$app->session->get('page-size')) {
    $main_check = 'checked';
  }
}

$this->registerJsVar('flashUpload', Yii::$app->session->hasFlash('upload') ? 1 : 0);
$this->registerJsVar('page_size', Yii::$app->session->get('page-size'));

?>
<div class="d-flex justify-content-start">
  <a href="<?= Url::toRoute(['/shop/product/export-main-catalog']) ?>" class="btn btn-success mb-3 js-main-catalog" <?= Yii::$app->session->has('items') ? 'hidden' : '' ?>>Export Main Catalog</a>
  <a href="<?= Url::toRoute(['/shop/product/export-main-catalog', 'items' => true]) ?>" class="btn btn-success mb-3 js-items-catalog" <?= !Yii::$app->session->has('items') ? 'hidden' : ''
                                                                                                                                      ?>>Export <span id="js-items-count"><?= Yii::$app->session->has('items') ? count(Yii::$app->session->get('items')) : 0
                                                                                                                                                                          ?></span>
    Items</a>
  <button data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-primary mb-3 ml-2">Upload </button>
  <?php if (!empty($products)) : ?>
    <button type="button" href="<?= Url::toRoute(['/shop/store/update-catalog']) ?>" class="btn btn-primary mb-3 ml-2" data-toggle="modal" data-target="#updateCatalog">Update catalog </button>
  <?php endif; ?>
</div>
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link <?= $active == 'all' ? 'active' : '' ?>" href="<?= Url::toRoute(['/shop/store/my-products']) ?>">All</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $active == 'connected' ? 'active' : '' ?>" href="<?= Url::toRoute(['/shop/store/my-products', 'StoreProductSearch[connected]' => 1]) ?>">Connected</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $active == 'not connected' ? 'active' : '' ?>" href="<?= Url::toRoute(['/shop/store/my-products', 'StoreProductSearch[connected]' => 0]) ?>">Not Connected</a>
  </li>
</ul>


<?= AdminGrid::widget([
  'title'                => 'Add a new product request',
  'dataProvider'         => $dataProvider,
  'extraSearch'          => $this->render('@app/modules/shop/views/store/_search', ['model' => $searchModel]),
  'filterModel'          => $searchModel,
  'panelHeadingTemplate' => '
<div class="d-flex justify-content-between  flex-wrap align-items-center">
<div class="d-flex justify-content-end align-items-start">{extraSearch}{export}</div>
    <div class="d-flex justify-content-start align-items-center">{createButton}{gridTitle}</div>

</div>',
  //'pjax'                 => false,
  'filterOnFocusOut'     => false,
  'createButton'         => Html::button('<div class="fa fa-plus"></div>', ['class' => 'btn btn-sm btn-light mr-4', 'data-toggle' => 'modal', 'data-target' => '#createForm']),
  //'createButton' => '<span></span>',
  'columns'              => [
    [
      'header'          => '<input type="checkbox" ' . $main_check . ' class="select-all" name="select_all" value="1">',
      'class'           => CheckboxColumn::class,
      'checkboxOptions' => function ($model, $i, $c) {
        $checked = false;
        if (Yii::$app->session->has('items') && in_array($i, Yii::$app->session->get('items'))) {
          $checked = true;
        }

        return ['checked' => $checked];
      },
    ],

    [
      'label'          => 'Title',
      'attribute'      => 'product_name',
      'headerOptions'  => ['style' => 'width:400px;'],
      'contentOptions' => ['style' => 'width:400px; white-space: normal;'],
      'value'          => function ($model) {
	$html = '<div><strong>'.$model->product_name.'</strong></div><div>SKU:';
	      return $html.Editable::widget([
			      'name'         => 'sku',
			      'asPopover'    => false,
			      'value'        => $model->sku,
			      'header'       => 'SKU',
			      'size'         => 'md',
			      'formOptions'  => [
				      'action' => Url::toRoute(['/shop/store/change-sku', 'id' => $model->id]),
			      ],
			      'options'      => ['class' => 'form-control', 'placeholder' => 'Enter a sku...'],
		      ]) . '</div>';
      },
      'format'         => 'raw',
      //'filter'         => false,
    ],
    [
      'label'     => 'Vol(ml)',
      'attribute' => 'vol',
    ],
    [
      'label'     => 'ABV(%)',
      'attribute' => 'abv',
    ],
    [
      'label'     => 'CAP',
      'attribute' => 'cap',
    ],
    'price:currency',
    [
      'attribute' => 'updated_at',
      'label'     => 'Last Update',
      'value'     => function ($model) {
        return Yii::$app->formatter->asDatetime($model->updated_at);
      },
    ],
    [
      'class'    => ActionColumn::class,
      'header'   => false,
      'width'    => false,
      'template' => '<div class="actions">{history}<span class="ml-3"></span>{update}</div>',
      'buttons'  => [
        'update'  => function ($url, $model) {
          return '<button class="js-add-offer" data-toggle="modal" data-target="#modalOffer" data-id="' . $model->id . '" style="text-decoration: none;border: none;background: none" type="button"><svg class="svg-inline--fa fa-pencil-alt fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pencil-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4.4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"></path></svg></button>';
        },
        'history' => function ($url, $model) {
          return '<a data-target="#history" class="js-history" data-id="' . $model->id . '" href="#"><i class="fas fa-history"></i></a>';
        },

      ],
    ],
  ],
]); ?>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" style="z-index: 1100" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Upload CSV</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php $form = ActiveForm::begin([
        'id'      => 'js-form-csv',
        'method'  => 'post',
        'options' => ['enctype' => 'multipart/form-data'],
        'action'  => Url::toRoute(['/shop/store/upload-provider']),
      ]); ?>
      <div class="modal-body px-4 py-0">

        <p class="text-sm mb-3">
          Make your csv like below. And send it to us admin@royal-batch.com. We will load it into the system</p>
        <p class="text-sm mb-3">Download a <a href="<?= Url::toRoute(['/shop/store/sample']) ?>" class="green-link">sample CSV template</a> to see an example of the preferred format.</p>
        <!--		    <p class="text-sm mb-3" style="color: red"><strong>Attention!!!</strong> Numeric fields must be without units. Right:750, Wrong:750ml</p>-->
      </div>
      <div class="modal-footer px-4">
        <!--        <button type="submit" id="js-upload-button" disabled class="btn btn-success">Upload file</button>-->
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      <?php ActiveForm::end() ?>
    </div>
  </div>
</div>

<!-- Error Modal -->
<?php if (Yii::$app->session->hasFlash('upload')) : ?>
  <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><?= Yii::$app->session->getFlash('upload')['title'] ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <?= Yii::$app->session->getFlash('upload')['message'] ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- Modal Offer-->
<div class="modal fade" id="modalOffer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Offer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php $form = ActiveForm::begin(['id' => 'js-form-product']) ?>
      <div class="modal-body">
        <h3 id="js-product-name"></h3>
        <div class="form-row">
          <div class="col-md-12">
            <?= $form->field($product, 'price')->input('number', ['min' => 0, 'step' => 0.01])->label('Price ($)') ?>
          </div>
          <?= $form->field($product, 'id')->hiddenInput()->label(false) ?>
          <div class="col-md-4">
            <?= $form->field($product, 'vol')->input('number', ['min' => 0, 'step' => 0.01, 'readonly' => Yii::$app->session->has('role') ? false : true])->label('Vol (ml)') ?>
          </div>
          <div class="col-md-4">
            <?= $form->field($product, 'abv')->input('number', ['min' => 0, 'step' => 0.01, 'readonly' => Yii::$app->session->has('role') ? false : true])->label('Abv (%)') ?>
          </div>
          <div class="col-md-4">
            <?= $form->field($product, 'cap')->input('number', ['min' => 0, 'step' => 1])->label('CAP') ?>
          </div>

          <div class="col-md-12">
            <?= $form->field($product, 'note')->textarea(['rows' => 6]) ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary js-form-prod-send">Save changes</button>
      </div>
      <?php ActiveForm::end() ?>
    </div>
  </div>
</div>
<!-- Modal Create-->
<div class="modal fade" id="createForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php $form = ActiveForm::begin() ?>
      <div class="modal-body">
        <h3 id="js-product-name"></h3>
        <div class="form-row">
          <div class="col-md-6">
            <?= $form->field($model, 'product_name') ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($model, 'price')->input('number', ['min' => 0, 'step' => 0.01])->label('Suggested Price,$') ?>
          </div>
          <div class="col-md-4">
            <?= $form->field($model, 'abv')->input('number', ['min' => 0, 'step' => 0.01])->label('ABV,%') ?>
          </div>
          <div class="col-md-4">
            <?= $form->field($model, 'vol')->input('number', ['min' => 0, 'step' => 0.01])->label('VOL,ml') ?>
          </div>
          <div class="col-md-4">
            <?= $form->field($model, 'cap')->input('number', ['min' => 0, 'step' => 1]) ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Send</button>
      </div>
      <?php ActiveForm::end() ?>
    </div>
  </div>
</div>


<!-- Update Catalog Modal -->
<div class="modal fade" id="updateCatalog" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Catalog</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php ActiveForm::begin(['id' => 'form-update-products', 'action' => '/shop/store/update-my-products']) ?>
        <div class="form-group">
          <h3>Select Products</h3>
          <?=
          Select2::widget([
            'name'          => 'product_id',
            'data'          => $products,
            'maintainOrder' => true,
            'options'       => ['multiple' => true],
            'pluginOptions' => [
              'tags'       => true,
              'allowClear' => true,
              'language'   => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
              ],

              'close'  => "function() { console.log('close'); }",
              'select' => "function() { console.log('select'); }",
            ],
          ]);
          ?>
        </div>
        <?php ActiveForm::end() ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary js-form-update">Update</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal History -->
<div class="modal fade" id="history" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Updated at</th>
              <th scope="col">Price</th>
            </tr>
          </thead>
          <tbody class="res-container">
            <tr>
              <td>2020</td>
              <td>23</td>
            </tr>
          </tbody>
        </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php $js = <<< JS
$(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
  if(flashUpload === 1){
  $('#errorModal').modal('show')
  }
  $('#w0-filters').css('display','none')
})
$(document).on('pjax:end',function() {
   let search = $('#storeproductsearch-product_name').val()
   let searchInput = $('#js-search-input')
  searchInput.val(search)
  searchInput.focus()
  $('#w0-filters').css('display','none')
})

 let upload = $('#csv-upload')
    $('.js-csv-upload').click(function () {
        upload.trigger('click');
    })
    upload.change(function () {
        $('.js-file-name').text(this.files.item(0).name)
        $('#js-upload-button').removeAttr('disabled')
    })

    $(document).on('click', '#js-upload-button', function () {
       let form = $('#js-form-csv')
       form.on('afterValidate',function(e,m,c) {
         if (c.length === 0){
           form.submit()
           $('#preloader').css('display','block')
         }
       })
    })

    $(document).on('click','.js-add-offer',function(e) {
      e.preventDefault()
      let id = $(this).data('id')
      $.post({
      url:'/shop/store/get-store-product',
      data:{id:id},
      success:function(res) {
        $('#js-product-name').text(res.product_name)
        $('#storeproduct-vol').val(res.vol)
        $('#storeproduct-abv').val(res.abv)
        $('#storeproduct-cap').val(res.cap)
        $('#storeproduct-price').val(res.price)
        $('#storeproduct-shipping').val(res.shipping)
        $('#storeproduct-sku').val(res.sku)
        $('#storeproduct-note').text(res.note)
        $('#storeproduct-id').val(id)
        $('#modalOffer').modal('show')

      }
      })
    })

    $(document).on('beforeSubmit','#js-form-product',function() {
      		let form = $(this)
           //$('#preloader').css('display','block')
           $.post({
           url:'/shop/store/update-product',
           data:form.serialize(),
           success:function() {
             //$.pjax.reload({container: "#w0-pjax", async: false})
           }
           })
       return false
    })

    // $(document).on('change','.select-on-check-all',function() {
    //
    //   let type = $(this).prop('checked') === true ? 'add' : 'remove'
    //   let values = []
    //
    //     let items = $('.kv-row-checkbox')
    //     items.each(function(i) {
    //       values.push($(this).val())
    //     })
    //
    //     $.post({
    //     url: '/shop/store/save-session-items',
    //     data:{type:type,values:values},
    //     success:function(res){
    //
    //       if (res === 0){
    //         $('.js-items-catalog').prop('hidden',true)
    //         $('.js-main-catalog').prop('hidden',false)
    //       } else{
    //          $('.js-items-catalog').prop('hidden',false)
    //         $('.js-main-catalog').prop('hidden',true)
    //         $('#js-items-count').text(res)
    //       }
    //
    //       $.pjax.reload({container: "#w0-pjax", timeout: false})
    //
    //     }
    //     })
    // })

    $(document).on('change','.select-all',function() {
     let type = $(this).prop('checked') === true ? 'add' : 'remove'
     let checked = type === 'add'
     let values = []
    	$('.kv-row-checkbox').each(function() {
    	  $(this).prop('checked',checked)
    	  values.push($(this).val())
    	})
    	$.post({
        url: '/shop/store/save-session-items',
        data:{type:type,values:values},
        success:function(res){
          if (res === '0'){
            $('.js-items-catalog').prop('hidden',true)
            $('.js-main-catalog').prop('hidden',false)
          } else{
             $('.js-items-catalog').prop('hidden',false)
            $('.js-main-catalog').prop('hidden',true)
            $('#js-items-count').text(res)
          }

        }
        })
    })

    $(document).on('change','.kv-row-checkbox',function() {
      let type = $(this).prop('checked') === true ? 'add' : 'remove'
      let i = 0
      let checked = [];

      $('.kv-row-checkbox').each(function() {
    	  if($(this).prop('checked') === true){
    	        	  checked.push($(this).val())
    	  }
    	})

      if(Number(page_size) === checked.length){
        $('.select-all').prop('checked',true)
      }else{
        $('.select-all').prop('checked',false)
      }

      let values = []
          values.push($(this).val())
        $.post({
        url: '/shop/store/save-session-items',
        data:{type:type,values:values},
        success:function(res){
          if (res === '0'){
            $('.js-items-catalog').prop('hidden',true)
            $('.js-main-catalog').prop('hidden',false)
          } else{
             $('.js-items-catalog').prop('hidden',false)
            $('.js-main-catalog').prop('hidden',true)
            $('#js-items-count').text(res)
          }

        }
        })
    })



//     $('#storeproductsearch-product_name').keyup(delay(function (e) {
//   let val = $(this).val()
//   $.get({
//   url:'/shop/store/my-products',
// data:{val:val},
// success:function() {
//   $.pjax.reload({container: "#w0-pjax", timeout: false})
// }
//   })
// }, 500));
$(document).on('click','.js-history',function(e) {
  e.preventDefault()
  let val = $(this).data('id')
  $.post({
  url:'/shop/store/get-history',
  data:{val:val},
  success:function(res) {
	  let layout = '';
	  let color = '';
	  for (let i = 0; i < res.length; i++) {
	    i === (res.length-1) ? color='green' : ''
	    layout += '<tr><td>' + res[i].updated_at + '</td><td style="color:'+color+'">' + res[i].price + '</td></tr>';
	  }
	  $('.res-container').html(layout);
	  $('#history').modal('show');
  }
  })

})

$(document).on('click','.js-form-update',function(){
let form = $('#form-update-products')
$('#preloader').css('display','block')
form.submit()
})
JS;
$this->registerJS($js) ?>
