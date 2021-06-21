<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use yii\helpers\Url;
use kartik\form\ActiveForm;
use app\modules\shop\models\Customer;
use app\modules\shop\widgets\CartWidget;

/**
 * @var $customer Customer
 */
$this->registerJsVar('customer', $customer);
?>
<!--<script src="https://www.paypal.com/sdk/js?components=hosted-fields,buttons&client-id=--><? //= PAYPAL_CLIENT ?><!--&merchant-id=--><? //= PAYPAL_MERCHANT ?><!--&currency=USD&intent=capture"-->
<script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENT ?>"> // Replace YOUR_SB_CLIENT_ID with your sandbox client ID
    </script>
<!--        data-client-token="--><? //= $client_token ?><!--"></script>-->
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
                        <a class="checkout-breadcrumb-link"
                           href="<?= Url::toRoute(['/shop/cart/information']) ?>">Information</a>
                        <svg xmlns="http://www.w3.org/2000/svg" class="checkout-breadcrumb-icon" viewBox="0 0 10 10">
                            <path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
                        </svg>
                    </li>
                    <li class="checkout-breadcrumb-item is-completed" aria-current="step">
                        <a class="checkout-breadcrumb-link" href="<?= Url::toRoute(['/shop/cart/ship']) ?>">Shipping</a>
                        <svg xmlns="http://www.w3.org/2000/svg" class="checkout-breadcrumb-icon" viewBox="0 0 10 10">
                            <path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
                        </svg>
                    </li>
                    <li class="checkout-breadcrumb-item is-current">
                        <span class="checkout-breadcrumb-text">Payment</span>
                    </li>
                </ul>
            </div>
	        <?php $form = ActiveForm::begin(['id' => 'my-sample-form']) ?>
	        <div class=" w-100">

                <div class="checkout-section  ">
                    <div class="col-md-12">

                        <div class="checkout-info">
                            <div class="checkout-info-row d-flex justify-content-between ">
                                <span>Contact
                                </span>
                                <span class="px-4 flex-grow-1 js-phone">
	                                <span><?= $customer->phone ?></span> , <span id="js-email-form"><?= $customer->email ?></span>
                                </span>
                                <span><a href="<?= Url::toRoute(['/shop/cart/information']) ?>"
                                         class="js-prev-step">Change</a>
                                </span>
                            </div>
                            <div class="checkout-info-row d-flex justify-content-between ">
                                <span>Ship to
                                </span>
                                <span class="px-4 flex-grow-1 js-address">
                                    <?= $customer->address . ' ' . $customer->adress_two . ', ' . $customer->city . ' ' . $customer->state . ' ' . $customer->zip . ', ' . $customer->contry ?>
                                </span>
                                <span><a href="<?= Url::toRoute(['/shop/cart/information']) ?>"
                                         class="js-prev-step">Change</a>
                                </span>
                            </div>
	                      </div>

                    </div>
                    <div class="checkout-section  ">


                        <div class="section-content text-left d-flex flex-wrap">
                            <div class="col-md-12">

                                <div class="checkout-section  ">
                                    <div class="checkout-section-header  ">
                                        <div class="col-md-12 w-100 text-left">

                                            <h2 class="checkout-section-title text-left">
                                                Billing address

                                            </h2>
                                            <p class="checkout-section-descr">
                                                Select the address that matches your card or payment method.
                                            </p>

                                        </div>

                                    </div>



                                </div>

                            </div>

                            <div class="section-content w-100 text-left d-flex flex-wrap">
                                <div class="col-md-12 ">

                                    <div class="shipping-method checkout-card fz-14 mb-3 p-0">
                                        <div class="shipping-method-header">

                                            <div class="d-flex justify-content-between pb-2 mb-2 border-bottom">
                                                <div class="form-group ">
                                                    <div class="form-check">
                                                        <input type="radio" id="delivery-address" name="billing-address"
                                                               class="form-check-input js-select-address" checked
                                                               value="0">
                                                        <label class="has-star form-check-label fz-14"
                                                               for="delivery-address">
                                                            Same as shipping address
                                                        </label>
                                                        <div class="help-block invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between pt-2">
                                                <div class="form-group ">
                                                    <div class="form-check">
                                                        <input type="radio" id="second-address" name="billing-address"
                                                               class="form-check-input js-select-address"
                                                               value="1">
                                                        <label class="has-star form-check-label fz-14"
                                                               for="second-address">
                                                            Use a different billing address
                                                        </label>
                                                        <div class="help-block invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
	                                        class="checkout-card-body border-top checkout-billing-address js-billing-address">

                                            <div class="row">

                                                <div class="form-group-relative col-md-6 pr-md-1 mb-1">
                                                    <div class="form-group mb-1 ">
                                                        <?= $form->field($customer, 'billing_first_name')
                                                                 ->input('text', ['placeholder' => "First name", 'value' => $customer->first_name, 'data-id' => $customer->first_name])
                                                                 ->label('First name') ?>
                                                    </div>
                                                </div>
                                                <div class="form-group-relative col-md-6 pl-md-1 mb-1">
                                                    <div class="form-group mb-1 ">
                                                        <?= $form->field($customer, 'billing_last_name')->input('text', [
	                                                        'placeholder' => "Last name",
	                                                        'value'       => $customer->last_name,
	                                                        'data-id'     => $customer->last_name,
                                                        ])->label('Last
                    name') ?>
                                                    </div>
                                                </div>
                                                <div class="form-group-relative col-md-12 mb-1 ">
                                                    <div class="form-group mb-1 ">
                                                        <?= $form->field($customer, 'billing_address')->input('text', [
	                                                        'placeholder' => "Address",
	                                                        'value'       => $customer->address,
	                                                        'data-id'     => $customer->address,
	                                                        'class'       =>
		                                                        'js-get-address',
                                                        ])->label('Address') ?>
                                                    </div>
                                                </div>
                                                <div class="form-group-relative col-md-12 mb-1 ">
                                                    <div class="form-group mb-1 ">
                                                        <?= $form->field($customer, 'billing_address_two')
                                                                 ->input('text', [
	                                                                 'placeholder' => "Apartment, suite, etc. (optional)",
	                                                                 'value'       => $customer->adress_two,
	                                                                 'data-id'     => $customer->adress_two,
	                                                                 'class'       => 'js-get-address',
                                                                 ])
                                                                 ->label('Apartment, suite, etc. (optional)') ?>
                                                    </div>
                                                </div>
                                                <div class="form-group-relative col-md-12 mb-1 ">
                                                    <div class="form-group mb-1 ">
                                                        <?= $form->field($customer, 'billing_city')
                                                                 ->input('text', ['placeholder' => "City", 'class' => 'js-get-address', 'data-id' => $customer->city, 'value' => $customer->city])
                                                                 ->label('City') ?>
                                                    </div>
                                                </div>
                                                <div
	                                                class="form-group-relative form-group-relative-visible col-md-4 mb-1 pr-md-1 ">
                                                    <div class="form-group mb-1 ">
                                                        <?= $form->field($customer, 'billing_country')->textInput(['value' => 'US', 'readonly' => true])->label('Country') ?>
                                                    </div>
                                                </div>
                                                <div
	                                                class="form-group-relative form-group-relative-visible col-md-4 mb-1 pr-md-1 ">
                                                    <div class="form-group mb-1 ">
                                                        <?= $form->field($customer, 'billing_state')->dropDownList($states, [
	                                                        'prompt'  => 'Select ...',
	                                                        'data-id' => $customer->state,
	                                                        'value'   => $customer->state,
	                                                        'class'   => 'js-input-zip js-get-address',
                                                        ])->label('States') ?>
                                                    </div>
                                                </div>
                                                <div class="form-group-relative col-md-4 mb-1 pl-md-1">
                                                    <div class="form-group mb-1 ">
                                                        <?= $form->field($customer, 'billing_zip')->input('text', [
	                                                        'placeholder' => "ZIP code",
	                                                        'class'       => 'js-get-address',
	                                                        'data-id'     => $customer->zip,
	                                                        'value'       => $customer->zip,
                                                        ])->label('ZIP code') ?>
                                                    </div>
                                                </div>
                                                <div class="form-group-relative col-md-12 mb-1  ">
                                                    <div class="form-group mb-1 ">
                                                        <?= $form->field($customer, 'billing_phone')->input('tel', [
	                                                        'placeholder' => "Phone",
	                                                        'data-id'     => $customer->phone,
	                                                        'value'       => $customer->phone,
                                                        ])->label('Phone') ?>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>


                                    </div>

                                </div>
                            </div>

	                         <div class="checkout-section-header  w-100">
                            <div class="col-md-12 w-100 text-left mt-3">

                                <h2 class="checkout-section-title text-left">
                                    Payment

                                </h2>
                                <p class="checkout-section-descr">
                                    All transactions are secure and encrypted.

                                </p>
<div id="paypal-button-container"></div>
                            </div>
		                         <!--		                         <div class="col-md-12">-->
		                         <!--			                         <div id="paypal-button-container"></div>-->
		                         <!--			                         <div align="center"> or </div>-->
		                         <!--			                          <div class="alert alert-warning alert-dismissible fade show pr-0" role="alert"-->
		                         <!--			                               style="display: none">-->
		                         <!--                                    <p><strong>DECLINED!</strong> <span id="js-error-message">You should check in on-->
		                         <!--                                            some of those fields below.</span></p>-->
		                         <!--                                </div>-->
		                         <!--                                <div class="checkout-card">-->
		                         <!--                                    <div class="checkout-card-header d-flex justify-content-between">-->
		                         <!--                                        <span class="checkout-card-title">Credit card </span>-->
		                         <!--                                        <span class="checkout-card-cards d-flex align-items-center">-->
		                         <!--                                            <span class="checkout-card-img mx-1">-->
		                         <!--                                                <img src="/images/card1.png" alt="">-->
		                         <!--                                            </span>-->
		                         <!--                                            <span class="checkout-card-img  mx-1">-->
		                         <!--                                                <img src="/images/card2.png" alt="">-->
		                         <!--                                            </span>-->
		                         <!--                                            <span class="checkout-card-img  mx-1">-->
		                         <!--                                                <img src="/images/card3.png" alt="">-->
		                         <!--                                            </span>-->
		                         <!--                                            <span class="checkout-card-img  mx-1">-->
		                         <!--                                                <img src="/images/card4.png" alt="">-->
		                         <!--                                            </span>-->
		                         <!--                                            <span class="checkout-card-img  mx-1 fz-14">-->
		                         <!--                                                and more...-->
		                         <!--                                            </span>-->
		                         <!--                                        </span>-->
		                         <!--                                    </div>-->
		                         <!---->
		                         <!--                                    <div class="checkout-card-body ">-->
		                         <!--                                        <div class="row">-->
		                         <!--                                            <div class="form-group-relative col-md-12 mb-1 ">-->
		                         <!--                                                --><? //= $form->field($card, 'number')->textInput([
		                         //	                                                'id'    => 'card-number',
		                         //	                                                'class' => 'card_field',
		                         //                                                ]) ?>
		                         <!--                                            </div>-->
		                         <!--                                            <div class="form-group-relative col-md-12 mb-1 ">-->
		                         <!--                                                --><? //= $form->field($card, 'card_name')->textInput(['value' => $customer->first_name . ' ' . $customer->last_name, 'id' => 'card-holder-name']) ?>
		                         <!--                                            </div>-->
		                         <!--                                            <div class="form-group-relative col-md-6 mb-1 pr-1">-->
		                         <!--                                                --><? //= $form->field($card, 'exp')->widget(MaskedInput::class, [
		                         //	                                                'name'          => 'exp-cc',
		                         //	                                                'id'            => 'expiration-date',
		                         //	                                                'clientOptions' => ['alias' => 'mm/yyyy'],
		                         //                                                ]) ?>
		                         <!--                                            </div>-->
		                         <!--                                            <div class="form-group-relative col-md-6 mb-1 pl-1">-->
		                         <!--                                                --><? //= $form->field($card, 'cvv')->passwordInput(['id' => 'cvv']) ?>
		                         <!--                                            </div>-->
		                         <!--                                        </div>-->
		                         <!--                                    </div>-->
		                         <!---->
		                         <!--                                </div>-->
		                         <!--		                         </div>-->

                        </div>
                        </div>

                    </div>

                </div>





                <div class="checkout-step-footer d-flex w-100">
                    <div class="col-md-12  d-flex justify-content-between align-items-center">
                        <a class="checkout-footer-link" href="<?= Url::toRoute(['/shop/cart/ship']) ?>">
                            <svg focusable="false" fill="#b59049" aria-hidden="true" class="checkout-footer-link-icon"
                                 role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
                                <path d="M8 1L7 0 3 4 2 5l1 1 4 4 1-1-4-4"></path>
                            </svg>
                            <span class="checkout-footer-link-text js-prev-step">Return to shipping</span>
                        </a>

	                    <!--                        <button type="submit" id="js-payment" class="checkout-footer-btn px-2 btn btn-primary mr-0  ">-->
	                    <!--                            <span class="btn-content ">Pay now</span>-->
	                    <!--                        </button>-->
                    </div>
                </div>
            </div>
	        <?php ActiveForm::end() ?>
        </div>
	    <?= CartWidget::widget() ?>
    </div>
</div>

<?php $js = <<< JS
$(document).on('change', '.js-select-address',function(e) {
  // $('input[name="name_of_your_radiobutton"]:checked').val();
  if($('input[name="billing-address"]:checked').val()==0){

    $('.js-billing-address').hide()
  }else{

     $('.js-billing-address').show()
  }
})


$(document).on('change','#second-address',function() {
    $('#customer-billing_first_name').val('')
    $('#customer-billing_last_name').val('')
    $('#customer-billing_address').val('')
    $('#customer-billing_address_two').val('')
    $('#customer-billing_city').val('')
    $('#customer-billing_state').val('')
    $('#customer-billing_zip').val('')
    $('#customer-billing_phone').val('')

})


	$(document).on("click", "#js-payment", function (e) {
    e.preventDefault()
    const form = $(this).closest('form');

$.ajax({
  type: "POST",
  url: "/payment/pay",
   data:form.serialize(),
    beforeSend() {
      $('#js-loader-text').text('Card is being verified. Please wait ...');
      $('#exampleModalCenter').modal('show');

    },
  success: function(res){
       $('#js-loader-text').text('Payment is in progress...');
       $.ajax({
       url:'/shop/cart/save-payment',
       type: "POST",
       success: function(){ $('#exampleModalCenter').modal('hide');}
       })
  },
  error: function(res) {
    console.log(res.responseText)
            $('#js-error-message').text(res.statusText)
            $('#js-loader-text').text(res.statusText)
       $('.alert.alert-warning.alert-dismissible').css('display','block')
       setTimeout(() => {
         $('#exampleModalCenter').modal('hide');
       }, 1500);
  }
});
  })

  let amount = $('#js-amount-total').data('total')

  $(document).on('pjax:end',function() {
   amount = $('#js-amount-total').data('total')
  })

     paypal.Buttons({
        createOrder: function(data, actions) {
          return actions.order.create({
      //      payer: {
      //   name: {
      //     given_name: $('#customer-billing_first_name').val(),
      //     surname: $('#customer-billing_last_name').val()
      //   },
      //   address: {
      //     address_line_1: $('#customer-billing_address').val(),
      //     address_line_2: $('#customer-billing_address_two').val(),
      //     admin_area_2: $('#customer-billing_city').val(),
      //     admin_area_1: $('#customer-billing_state').val(),
      //     postal_code:  $('#customer-billing_zip').val(),
      //     country_code: 'US'
      //   },
      //   email_address: customer.email,
      //   phone: {
      //     phone_type: "MOBILE",
      //     phone_number: {
      //       national_number: customer.phone
      //     }
      //   }
      // },
            purchase_units: [{
              amount: {
                value: amount.toFixed(2),
                currency_code: 'USD',
              },
              shipping:{
                address: {
          address_line_1: customer.address,
          address_line_2: customer.adress_two,
          admin_area_2: customer.city,
          admin_area_1: customer.state,
          postal_code:  customer.zip,
          country_code: 'US'
        },
              }
            }],
            application_context: {
        shipping_preference: 'SET_PROVIDED_ADDRESS'
      }

          });
        },
        onApprove: function(data, actions) {
           $('#exampleModalCenter').modal('show')
          return actions.order.capture().then(function(details) {
            let form = $('#my-sample-form');
               $.post({
        url:'/shop/cart/save-payment',
        data:{details:details,form:form.serialize()},
        // success:function() {
        //
        // }
        })
          });
        },
        onError: function(err) {
console.log(err);
},
      }).render('#paypal-button-container'); // Display payment options on your web page




      //Paypal
  // //Displays PayPal buttons
  //    paypal.Buttons({
  //      commit: false,
  //         createOrder: function(data, actions) {
  //          // This function sets up the details of the transaction, including the amount and line item details
  //          return actions.order.create({
  //            purchase_units: [{
  //              amount: {
  //                value: '2'
  //              }
  //            }]
  //          });
  //        },
  //        onCancel: function (data) {
  //            // Show a cancel page, or return to cart
  //         },
  //        onApprove: function(data, actions) {
  //          // This function captures the funds from the transaction
  //          return actions.order.capture().then(function(details) {
  //            // This function shows a transaction success message to your buyer
  //            alert('Thanks for your purchase!');
  //          });
  //        }
  //    }).render('#paypal-button-container');
  //    // Eligibility check for advanced credit and debit card payments
  //    console.log(paypal.HostedFields.isEligible())
  //    if (paypal.HostedFields.isEligible()) {
  //      paypal.HostedFields.render({
  //        createOrder: function () {return "order-ID";}, // replace order-ID with the order ID
  //        styles: {
  //          'input': {
  //            'font-size': '17px',
  //            'font-family': 'helvetica, tahoma, calibri, sans-serif',
  //            'color': '#3a3a3a'
  //          },
  //          ':focus': {
  //            'color': 'black'
  //          }
  //        },
  //        fields: {
  //          number: {
  //            selector: '#card-number',
  //            placeholder: 'card number'
  //          },
  //          cvv: {
  //            selector: '#cvv',
  //            placeholder: 'card security number'
  //          },
  //          expirationDate: {
  //            selector: '#expiration-date',
  //            placeholder: 'mm/yy'
  //          }
  //        }
  //      }).then(function (hf) {
  //        $('#my-sample-form').submit(function (event) {
  //          event.preventDefault();
  //          hf.submit({
  //            // Cardholder Name
  //            cardholderName: document.getElementById('card-holder-name').value,
  //            // Billing Address
  //            billingAddress: {
  //              streetAddress: document.getElementById('card-billing-address-street').value,      // address_line_1 - street
  //              extendedAddress: document.getElementById('card-billing-address-unit').value,       // address_line_2 - unit
  //              region: document.getElementById('card-billing-address-state').value,           // admin_area_1 - state
  //              locality: document.getElementById('card-billing-address-city').value,          // admin_area_2 - town / city
  //              postalCode: document.getElementById('card-billing-address-zip').value,           // postal_code - postal_code
  //              countryCodeAlpha2: document.getElementById('card-billing-address-country').value   // country_code - country
  //            }
  //          // redirect after successful order approval
  //          }).then(function () {
  //       window.location.replace('http://www.somesite.com/review');
  //     }).catch(function (err) {
  //       console.log('error: ', JSON.stringify(err));
  //       document.getElementById("consoleLog").innerHTML = JSON.stringify(err);
  //          });
  //        });
  //      });
  //    }
  //    else {
  //      $('#my-sample-form').hide();  // hides the advanced credit and debit card payments fields if merchant isn't eligible
  //    }
  //
JS;

$this->registerJs($js); ?>
