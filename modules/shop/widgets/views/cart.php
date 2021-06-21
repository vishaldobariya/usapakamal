<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use trntv\filekit\widget\Upload;
use app\modules\shop\models\Cart;
use app\modules\shop\models\Coupon;

/**
 * @var $coupon Coupon
 */
?>
<div class="checkout-sidebar px-sm-3 px-0 ">
	<span id="js-coupon-name" data-coupon="<?= Yii::$app->session->has('coupon') ? Yii::$app->session->get('coupon')->name : '' ?>"></span>
	<?php Pjax::begin(['id' => 'js-cart']) ?>
	<div class="checkout-products ">
    <?php $total = 0 ?>
		<?php $total_prod = 0 ?>
		<?php $eng_total = 0 ?>
		<?php foreach(Yii::$app->cart->positions as $key => $model) : ?>
			<?php if($model->formName() == 'Product') : ?>
				<div class="checkout-product w-100">
          <div class="checkout-product-img">
            <img src="<?= $model->thumb ?>" alt="">
            <span class="checkout-product-qwt" data-id="<?= $model->id ?>"><?= $model->quantity ?></span>
          </div>
          <div class="checkout-product-title">
            <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $model->slug]) ?>" class=" text-dark"><?= $model->name ?></a>
          </div>
          <div class="checkout-product-price">
            <?= Yii::$app->formatter->asCurrency($model->getPrice() * $model->quantity) ?>
          </div>
        </div>
				<?php $total += ($model->getPrice() * $model->quantity) ?>
				<?php $total_prod += ($model->getPrice() * $model->quantity) ?>
			<?php endif; ?>
      <?php if($model->formName() == 'Engraving') : ?>
				<div class="checkout-product w-100">
          <div class="checkout-product-img">
            <img src="<?= !empty($model->data[0]['front_image']) ? $model->data[0]['front_image']['base_url'] . $model->data[0]['front_image']['path'] : '/images/icon-custom.svg' ?>" alt="">
            <span class="checkout-product-qwt" data-id="<?= $model->id ?>"><?= $model->quantity ?></span>
          </div>
          <div class="checkout-product-title">
            <a href="#" class="js-edit-engraving" data-id="<?= $key ?>">Custom Engraving </a><br>
            <span><small><b>Engraving Message</b></small></span>
            <p class="mb-0 "> <small> <?= !empty($model->data[0]['front_line_1']) ? $model->data[0]['front_line_1'] : '' ?> </small></p>
            <p class="mb-0"><small> <?= !empty($model->data[0]['front_line_2']) ? $model->data[0]['front_line_2'] : '' ?></small> </p>
            <p class="mb-0"><small> <?= !empty($model->data[0]['front_line_3']) ? $model->data[0]['front_line_3'] : '' ?></small> </p>

          </div>
          <div class="checkout-product-price">
            <?= Yii::$app->formatter->asCurrency(\Yii::$app->settings->front_engraving * $model->quantity) ?>
          </div>
        </div>
				<?php $total += (float)Yii::$app->settings->front_engraving * $model->quantity ?>
				<?php $eng_total += (float)Yii::$app->settings->front_engraving * $model->quantity ?>
			<?php endif; ?>
		<?php endforeach; ?>
  </div>
  <div class="checkout-discount">
    <div class="checkout-section-header  ">
      <div class="col-md-12 w-100 text-left">
        <h2 class="checkout-section-title text-left">
          <input type="checkbox" <?= $order->note || $order->note_name || $order->note_email ? 'checked' : '' ?> class="js-show-message"> Gift Message

        </h2>
        <div class="js-show-gift-area" style="display: <?= $order->note || $order->note_name || $order->note_email ? 'block' : 'none' ?>">
          <div class="form-group highlight-addon field-order-note">
            <input type="text" id="order-note-name" class="form-control js-input-note" value="<?= $order->note_name ?>" placeholder="To Name" name="note_name">
          </div>
          <div class="form-group highlight-addon field-order-note">
            <input type="text" id="order-note-email" class="form-control js-input-note" value="<?= $order->note_email ?>" placeholder="To Email" name="note_email">
          </div>
          <div class="form-group highlight-addon field-order-note">
            <textarea id="order-note" class="form-control js-input-note" name="note" rows="3"><?= $order->note ?></textarea>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="checkout-discount">
    <div class="d-flex">
      <div class="form-group-relative  flex-grow-1 mb-0 mr-3 ">
        <div class="form-group mb-0 h-100 position-relative">
          <input name="coupon" class="js-coupon-name w-75 fz-14 h-100 form-control checkout-coupon-name" placeholder="Discount Code or Gift card" type="text">
          <div class="help-block invalid-feedback js-error position-absolute">Email cannot be blank.</div>
        </div>
      </div>
      <button class="btn btn-primary js-coupon-apply">
        Apply
      </button>
    </div>
  </div>
  <div class="checkout-total">
    <div class="checkout-total-row">
      <span>Product Total</span>
      <span><?= Yii::$app->formatter->asCurrency($total) ?></span>
    </div>
	  
	  <div class="checkout-total-row">
      <span><?= Yii::$app->session->get('shipping')['delivery'] ?? 'Shipping' ?></span>
      <span>
        <?php $ship = Yii::$app->formatter->asCurrency(Yii::$app->session->get('shipping') ?? 0) ?>
        <?= $ship ?>
      </span>
    </div>
	  
	  <?php $coup_price = 0 ?>
	  <?php if(Yii::$app->session->has('coupon')) : ?>
		  <?php $coupon = Yii::$app->session->get('coupon') ?>
		  <div class="checkout-total-row">
        <span>Coupon(<?= $coupon->name ?>)</span>
			  <?php
			
			  $coup_price = $coupon->getCouponPrice($total_prod);
			  ?>
			  <span>-<?= Yii::$app->formatter->asCurrency($coup_price) ?></span>
      </div>
	  <?php endif; ?>
	  <?php if($eng_total > 0) :?>
	  <div class="checkout-total-row">
      <span>Engraving </span>
		  <span><?= Yii::$app->formatter->asCurrency($eng_total) ?></span>
    </div>
	  <?php endif; ?>
    <div class="checkout-total-row">
      <span>Tax </span>
	    <?php $taxes = Cart::getTaxes($total_prod) ?>
	    <span><?= $taxes == 0 ? 'Zip is empty' : Yii::$app->formatter->asCurrency($taxes) ?></span>
    </div>
  </div>
  <div class="checkout-total-footer">

    <span> Total</span>
	  <?php $total -= $coup_price ?>
	  <?php $total += (Yii::$app->session->get('shipping') ?? 0) ?>
	  <?php $total += $taxes ?>
	  <span id="js-amount-total" data-total="<?= $total ?>"><?= Yii::$app->formatter->asCurrency($total) ?></span>

  </div>
	
	<?php Pjax::end() ?>
	<?php if(Yii::$app->controller->action->id !== 'information') : ?>
		<div class="d-flex justify-content-end">
      <button class="btn btn-primary js-save-for-later">
        Save for Later
      </button>
    </div>
	<?php endif; ?>

</div>


<!-- Modal Engrave Edit-->
<div class="modal fade" id="modalEngraving" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <?php ActiveForm::begin(['id' => 'js-engraving-form']) ?>
	    <input type="hidden" class="form-control mb-3" value="" id="pos_id" name="pos_id">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Custom Engraving</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Option 1</strong></p>
            <div class=" ">
              <input type="text" class="form-control mb-3" id="val-1" name="front[front_line_1]" placeholder="Line 1">
              <input type="text" class="form-control mb-3" id="val-2" name="front[front_line_2]" placeholder="Line 2">
              <input type="text" class="form-control mb-3" id="val-3" name="front[front_line_3]" placeholder="Line 3">
            </div>
          </div>
          <div class="col-md-6">
            <p><strong>Option 2</strong></p>
	          <?= Upload::widget([
		          'name'                => 'front[front_image]',
		          'options'             => [
			          'data-id' => '232',
		          ],
		          'files'               => [],
		          'hiddenInputId'       => true,
		          'url'                 => ['/storage/default/upload'],
		          'uploadPath'          => 'engraving/',
		          'sortable'            => true,
		          'maxNumberOfFiles'    => 1,
		          'showPreviewFilename' => false,
	
	          ]);
	
	          ?>
	          <p>Upload your logo file (.png). Please note that engraving logo should be in two colors only (Black and white).</p>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
	    <?php ActiveForm::end() ?>
    </div>
  </div>
</div>

<?php $js = <<< JS
$(document).ready(function() {
  let coupon = $('#js-coupon-name').data('coupon')
  
  if (coupon){
    $('.js-coupon-name').val(coupon)
    $('.js-coupon-apply').trigger('click')
  }
})
$(document).on('click','.js-coupon-apply',function() {
  let coupon = $('.js-coupon-name').val()
  //$('#exampleModalCenter').modal('show');
  $.post({
  url:'/shop/coupon/apply-coupon',
  data:{coupon:coupon},
  success:function(res) {
    if (res.status === 'Error'){
            $.pjax.reload({container: "#js-cart", timeout: false})
           setTimeout(function(){
             $('.js-error').css('display','block').text(res.message)
            $('.js-coupon-name').val(coupon)
            
            }, 1000);
    }else{
      $.pjax.reload({container: "#js-cart", timeout: false})
      $('.js-error').css('display','none')
      
    }
  }
  })
})

$(document).on('click','.js-save-for-later',function() {
  $('#exampleModalCenter').modal('show')
  $.post({
  url:'/shop/cart/save-for-later',
  success:function(res) {
  if (res.status === 'error'){
    $('#exampleModalCenter').modal('hide')
    swal({
  title: "Oops!",
  text: res.message,
  icon: "warning",
  button: true,
})
  }
  else{
    $('#exampleModalCenter').modal('hide')
        swal({
  title: "Success!",
  text: res.message,
  icon: "success",
  button: true,
})
setTimeout(function(){ window.location = res.url }, 3000);

  }
  }
  })
})


$(document).on('click','.js-edit-engraving',function(e) {
  e.preventDefault()
  let id = $(this).data('id')
  $.post({
  url:'/shop/cart/get-data-engraving-by-id',
  data:{id:id},
  success:function(res) {
    $('#pos_id').val(id)
    $('#val-1').val(res.front_line_1)
    $('#val-2').val(res.front_line_2)
    $('#val-3').val(res.front_line_3)
    if(res.front_image !==''){
      let image = res.front_image

      $('.files.ui-sortable').html(`
      <li class="upload-kit-item done image" value="0">
      <img src="`+image.base_url+image.path+`">
      <input name="front[front_image][path]" value="`+image.path+`" type="hidden">
      <input name="front[front_image][name]" value="`+image.name+`" type="hidden">
      <input name="front[front_image][size]" value="`+image.size+`" type="hidden">
      <input name="front[front_image][type]" value="`+image.type+`" type="hidden">
      <input name="front[front_image][order]" type="hidden" data-role="order">
      <input name="front[front_image][base_url]" value="`+image.base_url+`" type="hidden">
      <span class="name" title="1.jpg"></span>
      <span class="fas fa-times-circle remove" data-url="/storage/default/delete?path=`+image.path+`"></span>
      </li>
      `)
      $('.upload-kit-input').css('display','none')
    }
    $('#modalEngraving').modal('show')
  }
  })

})

$(document).on('beforeSubmit','#js-engraving-form',function() {
  let form = $(this).serialize()
  $.post({
  url:'/shop/cart/update-engraving',
  data:form,
  success:function() {
    $.pjax.reload({container: "#js-cart", timeout: false})
    $('#modalEngraving').modal('hide')
     swal({
  title: "Ok!",
  text: "Custom Engraving was updated",
  icon: "success",
  button: true,
});
  }
  })
  return false;
})

$(document).on('change','.js-input-note',function() {
  let val = $(this).val()
  let name = $(this).prop('name')
  $.post({
  url:'/shop/cart/add-gift',
  data:{val:val,name:name},
  })
})
$(document).on('change','.js-show-message',function() {
 if($(this).prop('checked') === true){
   $('.js-show-gift-area').css('display','block')
 }else{
      $('.js-show-gift-area').css('display','none')
      $('#order-note').val('')
      $('#order-note-name').val('')
      $('#order-note-email').val('')
      $.post({
      url:'/shop/cart/remove-gift',
      })

 }
})
JS;
$this->registerJs($js) ?>
