<?php

use yii\helpers\Url;
use kartik\form\ActiveForm;

$this->registerJsVar('flashUpload', Yii::$app->session->hasFlash('upload') ? 1 : 0);
//dd($products);
?>
<button class="btn btn-primary mb-2" data-toggle="modal" data-target="#exampleModalCenter">Upload CSV</button>
	<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">SKU</th>
      <th scope="col">Product Name</th>
	    <th scope="col"><?= $store->name ?></th>
    </tr>
  </thead>
		<tbody>
  <?php foreach($products as $key => $product) : ?>

	  <tr>
      <th scope="row"><?= $product->sku ?></th>
      <td><?= $product->product_name ?></td>
		  
			  <td><div class="d-flex justify-content-between"><div><span data-toggle="tooltip" data-placement="top"
			                                                             title="Old Price"><?= $product->old_price == '' ? 'new' : Yii::$app->formatter->asCurrency($product->old_price)
							  ?></span> | <span

							  style="color:
<?= $product->price > $product->old_price ?
								  'red' : 'green' ?>"
							  data-toggle="tooltip"
							  data-placement="top"
							  title="Current
			  Price"><?=
							  Yii::$app->formatter->asCurrency
							  ($product->price)
							  ?></span></div><div>
		  
		  
    </tr>
  <?php endforeach; ?>
  </tbody>
		
</table>

	<!-- Modal -->
	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
		    'action'  => Url::toRoute(['/shop/store/upload']),
	    ]); ?>
	    <div class="modal-body px-4 py-0">
        <p class="text-light text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
		    <?= $form->field($csv, 'store_name')->textInput(['value' => $store->name]) ?>
		    <div class="d-flex my-4 align-items-center">
	          
	              <?= $form->field($csv, 'csv')->fileInput(['id' => 'csv-upload', 'class' => 'd-none csv-upload'])->label(false) ?>

			    <!--	          <input id="csv-upload" type="file" name="csv" class="csv-upload">-->
            <button type="button" class="add-btn js-csv-upload mr-3">
              choose file
            </button>
            <span class="js-file-name">No file chosen</span>
          </div>
        <p class="text-sm mb-3">Download a <a href="<?= Url::toRoute(['/shop/store/sample']) ?>"
                                              class="green-link">sample CSV template</a> to see an example of the preferred format.</p>
      </div>
      <div class="modal-footer px-4">
        <button type="submit" id="js-upload-button" disabled class="btn btn-success">Upload file</button>
      </div>
	    <?php ActiveForm::end() ?>
    </div>
  </div>
</div>

<!-- Error Modal -->
<?php if(Yii::$app->session->hasFlash('upload')) : ?>
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

<?php $js = <<< JS
$(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
  if(flashUpload === 1){
  $('#errorModal').modal('show')
  }
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
    
$(document).on('click','.js-apply-price',function() {
  let button = $(this)
  let prev_span_find = $(this).parent().prev().find('span.js-find-price')
let price = prev_span_find.data('price')
let text_price = prev_span_find.text()
let shop_text_price = prev_span_find.data('text-shop-price')
let id = $(this).data('id')
let store_id = $(this).data('store-id')
$.post({
url:'/shop/store/update-price',
data:{id:id,price:price,store:store_id},
success:function() {
  button.prop('disabled',true)
  let next_td = button.parent().parent().parent().parent().find('td.js-main-catalog')
  next_td.find('span.js-dashboard-price').text(text_price)
  next_td.find('span.js-shop-price').text(shop_text_price)
}
})
})
JS;
$this->registerJS($js) ?>
