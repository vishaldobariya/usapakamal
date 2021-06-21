<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use yii\helpers\Url;
use kartik\form\ActiveForm;
use app\modules\shop\widgets\CartWidget;

?>

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

          <li class="checkout-breadcrumb-item is-completed">
	                      <a class="checkout-breadcrumb-link" href="<?= Url::toRoute(['/shop/cart/information']) ?>">Information</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="checkout-breadcrumb-icon" viewBox="0 0 10 10">
              <path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
            </svg>
          </li>
          <li class="checkout-breadcrumb-item is-current" aria-current="step">
            <span class="checkout-breadcrumb-text">Shipping</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="checkout-breadcrumb-icon" viewBox="0 0 10 10">
              <path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
            </svg>
          </li>
          <li class="checkout-breadcrumb-item is-blank">
            <span class="checkout-breadcrumb-text">Payment</span>
          </li>
        </ul>
      </div>
	    <?php $form = ActiveForm::begin() ?>
	    <div class=" w-100">

          <div class="checkout-section  ">
            <div class="col-md-12">

              <div class="checkout-info">
                <div class="checkout-info-row d-flex justify-content-between ">
                  <span>Contact
                  </span>
                  <span class="px-4 flex-grow-1 js-phone">
				<?= $customer->phone . ', ' . $customer->email ?>
                  </span>
                  <span><a href="<?= Url::toRoute(['/shop/cart/information']) ?>" class="js-prev-step">Change</a>
                  </span>
                </div>
                <div class="checkout-info-row d-flex justify-content-between ">
                  <span>Ship to
                  </span>
                  <span class="px-4 flex-grow-1 js-address">
				<?= $customer->address . ' ' . $customer->adress_two . ', ' . $customer->city . ' ' . $customer->state . ' ' . $customer->zip . ', ' . $customer->contry ?>
                  </span>
                  <span><a href="<?= Url::toRoute(['/shop/cart/information']) ?>" class="js-prev-step">Change</a>
                  </span>
                </div>

              </div>

            </div>
            <div class="checkout-section  ">
              <div class="checkout-section-header  ">
                <div class="col-md-12 w-100 text-left">

                  <h2 class="checkout-section-title text-left">
                    Shipping method

                  </h2>
                  <p class="checkout-section-descr">
                    We do not ship to PO BOX. 21 or older signature required upon all deliveries.
                    Orders are shipped out 1-3 days after order Friday orders are shipped out
                    Monday-Tuesday.



                  </p>

                </div>

              </div>
	                  <div class="section-content text-left d-flex flex-wrap">
                <div class="col-md-12">

                  <div class="shipping-method fz-14">
                    <div class="d-flex justify-content-between">
	                    <input id="delivery_ground" class="js-ship" data-price="<?= $ship_ground ?>" data-type="ground" name="shipping"
	                           type="radio" <?= Yii::$app->session->has('shipping_type') ? 'checked' : '' ?>>
	                         <label class="has-star form-check-label fz-14" for="delivery_ground">
                            ground shipping
                          </label>
	                    <div class="shipping-method-price">$<span id="js-ground-price"><?= $ship_ground ?></span></div>
                    </div>

                  </div>
	                <div class="shipping-method fz-14">
                    <div class="d-flex justify-content-between">
	                    <input id="delivery" name="shipping" class="js-ship" data-price="<?= $ship_base ?>" data-type="base" type="radio" <?= Yii::$app->session->has('shipping_type') ? '' : 'checked'
	                    ?>>
	                         <label class="has-star form-check-label fz-14" for="delivery">
                           2 Days shipping
                          </label>
	                    <div class="shipping-method-price">$<span id="js-base-price"><?= $ship_base ?></span></div>
                    </div>

                  </div>

                </div>

              </div>
	            
            </div>

          </div>
          <div class="checkout-step-footer d-flex w-100">
            <div class="col-md-12  d-flex justify-content-between align-items-center">
              <a class="checkout-footer-link" href="/">
                <svg focusable="false" fill="#b59049" aria-hidden="true" class="checkout-footer-link-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
                  <path d="M8 1L7 0 3 4 2 5l1 1 4 4 1-1-4-4"></path>
                </svg>
                <span class="checkout-footer-link-text js-prev-step">Return to information</span>
              </a>

              <button type="submit" class="checkout-footer-btn px-2 btn btn-primary mr-0 js-next-step">
                <span class="btn-content ">Continue to
                  payment</span>
              </button>
            </div>
          </div>
        </div>
	
	    <?php ActiveForm::end() ?>
</div>
	  <?= CartWidget::widget() ?>
</div>

</div>

<?php $js = <<< JS
$(document).on('change','.js-delivery',function() {
  let price = $(this).data('price')
  let delivery = $(this).val()
  let code = $(this).data('code')
  let service_code = $(this).data('service-code')
  $.post({
  url:'/shop/cart/fedex',
  data:{price:price,delivery:delivery,code:code,service_code:service_code},
  success:function() {
   $.pjax.reload({container: "#js-cart", timeout: false})
  }
  })
})

$(document).on('change','.js-ship',function() {
  let price = $(this).data('price')
  let type = $(this).data('type')
  $.post({
  url:'/shop/cart/change-shipping',
  data:{price:price,type:type},
  success:function() {
   $.pjax.reload({container: "#js-cart", timeout: false})
  }
  })
})
JS;
$this->registerJs($js) ?>
