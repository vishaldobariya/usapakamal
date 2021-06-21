<?php

use yii\helpers\Url;
use kartik\form\ActiveForm;
use app\modules\shop\widgets\CartWidget;

?>
<!-- <script src="https://js.stripe.com/v3/"></script> -->
<div class="page checkout pb-5">
  <div class="checkout-inner">
    <div class="checkout-main">
      <div class="checkout-main-header">
        <a class="checkout-logo" href="/">
          RoyalBatch
        </a>
        <ul class="checkout-breadcrumb ">
          <li class="checkout-breadcrumb-item is-completed">
            <a class="checkout-breadcrumb-link" href="<?= Url::toRoute(['/shop/cart/cart']) ?>">Cart</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="checkout-breadcrumb-icon" viewBox="0 0 10 10">
              <path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
            </svg>
          </li>

          <li class="checkout-breadcrumb-item is-current" aria-current="step">
            <span class="checkout-breadcrumb-text">Information</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="checkout-breadcrumb-icon" viewBox="0 0 10 10">
              <path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
            </svg>
          </li>
          <li class="checkout-breadcrumb-item is-blank">
            <span class="checkout-breadcrumb-text">Shipping</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="checkout-breadcrumb-icon" viewBox="0 0 10 10">
              <path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
            </svg>
          </li>
          <li class="checkout-breadcrumb-item is-blank">
            <span class="checkout-breadcrumb-text">Payment</span>
          </li>
        </ul>

        <div class="checkout-express d-none">
          <h2 class="checkout-express-title">
            Express checkout
          </h2>

          <div class="checkout-express-buttons">
            <a href="#" class="checkout-express-btn  checkout-express-shop-buy">
              <img src="/images/shop-byu.svg" alt="">
            </a>
            <a href="#" class="checkout-express-btn checkout-express-g-pay">
              <img src="/images/g-pay.svg" alt="">
            </a>
          </div>
          <div>
            <div class="checkout-separator">
              <span class="checkout-separator-content">
                OR
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="checkout-main-content">

        <?php $form = ActiveForm::begin(['id' => 'js-shipping-form', 'options' => ['class' => 'row'],]) ?>
	      <div class="checkout-step is-active  w-100">

          <div class="checkout-section  ">
            <div class="checkout-section-header  ">
              <div class="col-md-12 d-flex  justify-content-between w-100">

                <h2 class="checkout-section-title">
                  <!--                  Contact information-->
                </h2>
	              <?php if(Yii::$app->user->isGuest) : ?>
		              <p class="checkout-section-link">
                    <span>Already have an account?</span>
                    <a href="#" data-toggle="modal" data-target="#loginModal">
                      Log in
                    </a>
                  </p>
	              <?php elseif(!empty($addresses) && count($addresses) > 1): ?>
		              <p class="checkout-section-link">
                    <a href="#" data-toggle="modal" data-target="#addressModal">
                      Select a shipping address from my addresses
                    </a>
                  </p>
	              <?php endif; ?>
              </div>

            </div>

            <div class="checkout-section  ">
              <div class="checkout-section-header  ">
                <div class="col-md-12 d-flex  justify-content-between w-100">

                  <h2 class="checkout-section-title">
                    Shipping address

                  </h2>

                </div>

              </div>

              <div class="section-content d-flex flex-wrap">
                <div class="form-group-relative col-md-12 mb-1 ">
                  <div class="form-group mb-1 ">
                    <?= $form->field($customer, 'email')->input('text', ['placeholder' => "Email", 'readonly' => !Yii::$app->user->isGuest])->label('Email') ?>
                  </div>
                </div>
                <div class="form-group-relative col-md-6 pr-md-1 mb-1">
                  <div class="form-group mb-1 ">
                    <?= $form->field($customer, 'first_name')->input('text', ['placeholder' => "First name"])->label('First name') ?>
                  </div>
                </div>
                <div class="form-group-relative col-md-6 pl-md-1 mb-1">
                  <div class="form-group mb-1 ">
                    <?= $form->field($customer, 'last_name')->input('text', ['placeholder' => "Last name"])->label('Last name') ?>
                  </div>
                </div>
                <div class="form-group-relative col-md-12 mb-1 ">
                  <div class="form-group mb-1 ">
                    <?= $form->field($customer, 'address')->input('text', ['placeholder' => "Address"])->label('Address') ?>
                  </div>
                </div>
                <div class="form-group-relative col-md-12 mb-1 ">
                  <div class="form-group mb-1 ">
                    <?= $form->field($customer, 'adress_two')
                             ->textInput()
                             ->input('text', ['placeholder' => "Apartment, suite, etc. (optional)"])
                             ->label('Apartment, suite, etc. (optional)') ?>
                  </div>
                </div>
	             
                    <?= $form->field($customer, 'contry')->hiddenInput(['value' => 'US', 'readonly' => true])->label(false) ?>
                
	               <div class="form-group-relative col-md-4 mb-1 pr-md-1">
                  <div class="form-group mb-1 ">
                    <?= $form->field($customer, 'zip',['enableClientValidation' => false,])->input('text', ['placeholder' => "ZIP code"])->label('ZIP code') ?>
                  </div>
                </div>
                <div class="form-group-relative col-md-4 mb-1 ">
                  <div class="form-group mb-1 ">
                    <?= $form->field($customer, 'city')->input('text', ['placeholder' => "City"])->label('City') ?>
                  </div>
                </div>
               
                <div class="form-group-relative  col-md-4 mb-1 pl-md-1 ">
                  <div class="form-group mb-1 ">
                    <?= $form->field($customer, 'state')->dropDownList($states, ['prompt' => 'Select state...', 'class' => 'js-input-zip'])->label('States') ?>
                  </div>
                </div>
               
                <div class="form-group-relative col-md-12 mb-1  ">
                  <div class="form-group mb-1 ">
                    <?= $form->field($customer, 'phone')->input('tel', ['placeholder' => "Phone"])->label('Phone') ?>
                  </div>
                </div>
              </div>

              <div class="section"></div>
            </div>

          </div>
          <div class="checkout-step-footer d-flex w-100">
            <div class="col-md-12  d-flex justify-content-between align-items-center">
              <a class="checkout-footer-link" href="<?= Url::toRoute(['/shop/cart/cart']) ?>">
                <svg focusable="false" fill="#b59049" aria-hidden="true" class="checkout-footer-link-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
                  <path d="M8 1L7 0 3 4 2 5l1 1 4 4 1-1-4-4"></path>
                </svg>
                <span class="checkout-footer-link-text">Return to cart</span>
              </a>

              <button type="button" class="checkout-footer-btn px-2 btn btn-primary mr-0 js-next-step">
                <span class="btn-content js-calculate-shipping">Continue to
                  shipping</span>
              </button>
            </div>
          </div>
        </div>

	      <!-- Modal Address -->
        <div class="modal fade" id="modalAddress" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Is the shipping address correct ?</h5>
              </div>
              <div class="modal-body text-left">
                <small>Address:</small>
                <p id="modal-address"></p>
                <small>Apartment, suite, etc. (optional):</small>

                <p id="modal-address-two"></p>
                <small>City:</small>
                <p id="modal-city"></p>
                <small>Country:</small>
                <p>US</p>
                <small>State:</small>
                <p id="modal-state"></p>
                <small>Zip:</small>
                <p id="modal-zip"></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-3 d-block d-sm-inline-block" data-dismiss="modal">Edit Adress</button>
                <button type="submit" class="btn btn-secondary mb-3  d-sm-inline-block">Continue to
                  shipping</button>
              </div>
            </div>
          </div>
        </div>


	      <?php ActiveForm::end() ?>


      </div>
    </div>
	  <?= CartWidget::widget() ?>
  </div>

</div>

<!-- Modal Login-->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	    <?php $form = ActiveForm::begin(['id' => 'js-login-form']) ?>
	    <div class="modal-body">
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary js-login-button">Login</button>
      </div>
	    <?php ActiveForm::end() ?>
    </div>
  </div>
</div>


<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">My Addresses</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

	    <div class="modal-body">
		    <ol>
		    <?php foreach($addresses as $address) : ?>
			    <li> <a href="#" class="js-select-address" data-id="<?= $address->id ?>"><?= $address->full ?></a></li>
		    <?php endforeach; ?>
		    </ol>
	    </div>
	    <div class="modal-body text-center">
        <p>Just click on any address above</p>
      </div>
    </div>
  </div>
</div>


<?php

$js = <<< JS
$(document).on('change','#customer-zip',function() {
  let zip = $(this).val()
  let elem = $(this)
  $.post({
  url:'/shop/cart/get-data-zip',
  data:{zip:zip},
  success:function(res) {
    if (res.status === 'error'){
      elem.addClass('is-invalid ')
      elem.next().text(res.message)
      elem.next().css('display','block')
    }
    else{
      $('#modal-zip').text(zip)
      $('#customer-city').val(res.city).trigger('change')
      $('#customer-state').val(res.state).trigger('change')
      elem.next().css('display','none')
      elem.removeClass('is-invalid ')
    }
  }
  })
})

$(document).on('beforeSubmit','#js-login-form',function() {
  let form = $(this)
  $.post({
  url:'/sign/cart-login',
  data:form.serialize(),
  success:function(res) {
  if (res.status === 'error'){
    let elem = $('#'+res.id)
    elem.addClass('is-invalid')
    elem.next().text(res.message)
  } else{
    window.location.reload()
  }
  }
  })
  return false
})



let timeout = null;
$(document).on('change','.js-input-zip',function() {

  let elem = $('#customer-zip')
  let zip = elem.val()
  let state = $(this).val()
  if(zip){
       $.post({
  url:'/shop/cart/validate-zip',
  data:{zip:zip,state:state},
  success:function(res) {
    if (res.status === 'error'){
      elem.addClass('is-invalid ')
      elem.next().text(res.message)
      elem.next().css('display','block')
    }
    else{
      $('#modal-zip').text(zip)
      $('#modal-state').text(state)
      elem.next().css('display','none')
      elem.removeClass('is-invalid ')
    }
  }
  })
  }
  // clearTimeout(timeout);
  //
  // timeout = setTimeout(() => {
  //     $.post({
  //     url:'/shop/cart/shipping',
  //     data:{state:$(this).val()},
  //     success:function() {
  //             $.pjax.reload({container: "#js-cart", timeout: false})
  //     },
  //     })
  // }, 1000);
});

$(document).ready(function() {
    $('#modal-address').text($('#customer-address').val())
    $('#modal-address-two').text($('#customer-adress_two').val())
    $('#modal-city').text($('#customer-city').val())
    $('#modal-state').text($('#customer-state').val())
    $('#modal-zip').text($('#customer-zip').val())

})

$(document).on('change','#customer-phone',function() {
  let val = $(this).val()
  $('.js-phone').text(val)
})

$(document).on('change','#customer-address',function() {
  $('#modal-address').text($(this).val())
})
$(document).on('change','#customer-adress_two',function() {
  $('#modal-address-two').text($(this).val())
})
$(document).on('change','#customer-city',function() {
  $('#modal-city').text($(this).val())
})


$(document).on('click','.js-next-step',function(e) {
  let form = $('#js-shipping-form')
	let inputs = form.find("input")
	inputs.each(function() {
	  if($(this).prop('id') !== ''){
	    form.yiiActiveForm('validateAttribute', $(this).prop('id'))
	  }
	})
	setTimeout(function(){
	   if(form.find(".has-error").length === 0) {
       $('#modalAddress').modal('show')
   }
	 }, 800);

})

$(document).on('change','#customer-email',function() {
  let email = $(this).val()
  $.post({
  url:'/shop/cart/find-info',
  data:{email:email},
  success:function(res) {
  if (res.status === 'ok'){
    $('#customer-first_name').val(res.first_name).trigger('change')
    $('#customer-last_name').val(res.last_name).trigger('change')
    $('#customer-address').val(res.address).trigger('change')
    $('#customer-adress_two').val(res.address_two).trigger('change')
    $('#customer-city').val(res.city).trigger('change')
    $('#customer-state').val(res.state).trigger('change')
    $('#customer-zip').val(res.zip).trigger('change')
    $('#customer-phone').val(res.phone).trigger('change')
  }
  }
  })
})

$(document).on('click','.js-select-address',function() {
  let id = $(this).data('id')
  $.post({
  url:'/shop/cart/select-address',
  data:{id:id},
  success:function(res) {
    $('#customer-address').val(res.address).trigger('change')
    $('#customer-adress_two').val(res.address_two).trigger('change')
    $('#customer-city').val(res.city).trigger('change')
    $('#customer-state').val(res.state).trigger('change')
    $('#customer-zip').val(res.zip).trigger('change')
    $('#customer-first_name').val(res.first_name).trigger('change')
    $('#customer-last_name').val(res.last_name).trigger('change')
    $('#addressModal').modal('hide')
   // $('.modal-backdrop').remove()
  }
  })
})

JS;
$this->registerJs($js);
?>
