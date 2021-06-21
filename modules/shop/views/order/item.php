<?php

use yii\helpers\Url;
use app\modules\shop\models\Order;
use app\modules\shop\models\Customer;
use app\modules\shop\models\OrderItem;

/**
 * @var $order    Order
 * @var $customer Customer
 */

?>
<script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENT ?>">
</script>

	<div class="row" id="dragdrop">
    <div class="col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="stat-widget-two">
                    <div class="media text-center">
                        <div class="media-body">
                            <h3 class="mt-0 mb-1 text-dark js-order-id" data-id="<?= $order->id ?>"><strong>#<?= $order->id + 10000 ?></strong></h3><span
		                        class=""><?= date('F j, Y', $order->created_at)
		                        ?></span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
		<?php if(Yii::$app->user->identity->role == 'user' && $order->status == 6) : ?>
			<div class="col-md-3 col-sm-12">
            <a href="<?= Url::toRoute(['/shop/order/continue', 'id' => $order->id]) ?>">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="stat-widget-two">
                            <div class="media">
                                <div class="media-body">

                                    <h3 class="mt-0 mb-1 text-dark "><strong>NOT PAID</strong></h3><span class="">
                                        <span class="h3  ">Click on this box to pay</span>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
		<?php else : ?>
			<div class="col-md-3 col-sm-12">
            <div class="card text-center">
                <div class="card-body">
                    <div class="stat-widget-two">
                        <div class="media">
                            <div class="media-body">
                                <h3 class="mt-0 mb-1 text-dark "><strong>PAID</strong></h3><span class="">
                                    <span class="h3  ">
                                        <?php if($order->status == 6) : ?>
	                                        <i class="fa fa-times text-danger" style="font-size: 24px;"></i>
                                        <?php else : ?>
	                                        <i class="fa fa-check text-success" style="font-size: 24px;"></i>
                                        <?php endif; ?>
                                    </span>
                                </span>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php endif; ?>
		<div class="col-md-3 col-sm-12">
        <div class="card text-center">
            <div class="card-body">
                <div class="stat-widget-two">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mt-0 mb-1 text-grey"><strong><?= Yii::$app->formatter->asCurrency($order->total_cost) ?></strong></h3><span class="">Total Summary</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="card text-center">
            <div class="card-body">
                <div class="stat-widget-two">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mt-0 mb-1 text-grey"><strong>STATUS</strong></h3><span class=""><?= Order::STATUSES[$order->status] ?></span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


	<div class="row mb-4">

    <div class="col-lg-12 " style="line-height: 1.4;">
        <div class="table-responsive">
            <table class="table invoice-details-table" style="min-width: 500px;">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th class="text-center w-25">Engraving</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">Website Price</th>
	                    <!--                        <th class="text-center">Status</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php $sum = 0; ?>
                    <?php $total_eng = 0 ?>
                    <?php foreach($order->items as $item) : ?>
	                    <?php
	
	                    /**
	                     * @var $item OrderItem
	                     */
	
	                    ?>
	                    <tr>
                            <td><img width="" height="auto" class="table-img img-responsive table-img-sm" src="<?= $item->product->thumb ?>"></td>
                            <td><?= $item->product->name ?></td>
                            <td class="text-center w-25">
                             
	                            <?php if(!empty($item->engravings)) : ?>
		                            <button type="button" data-item="<?= $item->id ?>" class="btn btn-primary js-see-engraving">See Engravings</button>
		
		                            <?php foreach($item->engravings as $engraving): ?>
			                            <?php $total_eng += $engraving->front_price * $engraving->qty ?>
		                            <?php endforeach; ?>
	                            <?php endif; ?>
                            </td>
		                    <td class="text-center"><?= $item->qty ?>
                            </td>
                            <td class="text-center"><span><?= Yii::$app->formatter->asCurrency($item->product_price) ?></span>
                        </tr>
	                    <?php $sum += $item->qty * $item->product_price; ?>
                    <?php endforeach; ?>
                    <tr>

                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center" style="white-space: nowrap;">TOTAL COST</td>

                        <td class="text-center"> <?= Yii::$app->formatter->asCurrency($sum) ?>
                        </td>

                    </tr>
                    <?php if($total_eng > 0) : ?>
	                    <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center nowrap" style="white-space: nowrap;">ENGRAVING</td>
                            <td class=" text-center"><?= Yii::$app->formatter->asCurrency($total_eng) ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center nowrap" style="white-space: nowrap;">TAX(%<?= $order->tax_percent ?>)</td>
                        <td class=" text-center"><?= Yii::$app->formatter->asCurrency($order->tax) ?>
                        </td>

                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center nowrap" style="white-space: nowrap;">SHIPPING</td>
                        <td class=" text-center"><?= Yii::$app->formatter->asCurrency($order->ship_price) ?>
                        </td>

                    </tr>
                    <?php if($order->coupon != '') : ?>
	                    <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center nowrap" style="white-space: nowrap;">DISCOUNT</td>
                            <td class=" text-center">-<?= Yii::$app->formatter->asCurrency($coup_price) ?>
                            </td>

                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center nowrap" style="white-space: nowrap;">TOTAL</td>
                        <td class=" text-center js-total" data-total="<?= $order->total_cost ?>"><?= Yii::$app->formatter->asCurrency($order->total_cost) ?>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?php $customer = $order->customer ?>
<div class="row mb-5">
    <div class="col-xl-12 col-sm-6 col-xxl-6">
        <div class="card h-100">
            <div class="card-body">
                <h4 class="card-title px-0 mb-5">BILLING ADDRESS
                </h4>
                <div class="mb-4 text-left">
                    <h4 class="mb-0 item"><strong><?= $customer->name ?? '' ?></strong></h4>
                </div>
                <div class="mb-4 text-left">
                    <h5 class="mb-0 item lh-16">
                        <?= $customer->fullBillingAddress ?? '' ?>
                    </h5>
                </div>
                <div class="mb-4 text-left">
                    <h5 class="mb-0 item">
                        <a href="tel:<?= $customer->phone ?? '' ?>">

                            <i class="fa fa-phone"></i> <?= $customer->phone ?? '' ?>
                        </a>
                    </h5>
                </div>
                <div class="mb-4 text-left">
                    <h5 class="mb-0 item">
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:<?= $customer->email ?? '' ?>"> <?= $customer->email ?? '' ?>
                        </a>
                    </h5>
                </div>

            </div>
        </div>
    </div>
    <div class="col-xl-12 col-sm-6 col-xxl-6">
        <div class="card h-100">
            <div class="card-body">
                <h4 class="card-title px-0 mb-5"> SHIPPING ADDRESS
                </h4>
	             <div class="mb-4 text-left">
                    <h4 class="mb-0 item"><strong><?= $customer->name ?? '' ?></strong></h4>
                </div>
                <div class="mb-4 text-left">
                    <h5 class="mb-0 item lh-16">
                        <?= $customer->fullAddress ?? '' ?>
                    </h5>
                </div>
     <div class="mb-4 text-left">
                    <h5 class="mb-0 item">
                        <a href="tel:<?= $customer->phone ?? '' ?>">

                            <i class="fa fa-phone"></i> <?= $customer->phone ?? '' ?>
                        </a>
                    </h5>
                </div>
                <div class="mb-4 text-left">
                    <h5 class="mb-0 item">
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:<?= $customer->email ?? '' ?>"> <?= $customer->email ?? '' ?>
                        </a>
                    </h5>
                </div>

            </div>
        </div>
    </div>

</div>
	<div class="row mb-5">
    <div class="col-xl-12 col-sm-12 col-xl-6">
        <div class="card h-100">
            <div class="card-body">
                <h4 class="card-title px-0 mb-5"> Additional Information
                </h4>

                <div class="row mb-4">

                    <div class="col-12 flex-grow-1"><b class="w-900 pr-3">Email: </b><?= $order->note_email != '' ? $order->note_email : 'No email' ?></div>
                    <div class="col-12 flex-grow-1"><b class="w-900 pr-3">Name: </b><?= $order->note_name != '' ? $order->note_name : 'No name' ?></div>
                    <div class="col-12 flex-grow-1"><b class="w-900 pr-3">Message: </b><?= $order->note != '' ? $order->note : 'No message' ?></div>
                </div>



            </div>
        </div>
    </div>


</div>

<!-- Modal Engravings-->
<div class="modal fade" id="seeEngraving" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Engraving</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row js-insert-engraving">
                                 
                                    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<?php $js = <<< JS
let amount = $('.js-total').data('total')

$(document).on('click','.js-see-engraving',function() {
 let item = $(this).data('item')
 $.post({
 url:'/shop/order/get-engravings',
 data:{item:item},
 success:function(res) {
   let insert = $('.js-insert-engraving')
 insert.html('')
 insert.html(res)
 $('#seeEngraving').modal('show')
 }
 })
})


paypal.Buttons({
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: amount.toFixed(2)
              }
            }]
          });
        },
        onApprove: function(data, actions) {
           $('#exampleModalCenter').modal('show')
          return actions.order.capture().then(function(details) {
            let form = $('#my-sample-form');
               $.post({
        url:'/shop/order/save-payment',
        data:{details:details},

        })
          });
        }
      }).render('#paypal-button-container');

$(document).on('click','.js-check-address',function() {
  $('#checkShip').modal('show')
})

$(document).on('beforeSubmit','#add-address',function() {
  let form = $(this).serialize()
  $.post({
  url:'/admin/dashboard/add-address',
  data:form,
  success:function(res) {
  if (res.status === 'ok'){
          $.pjax.reload({container: "#container-addresses", timeout: false})

  } else{
    $('input#shippingaddress-'+res.errors.key).next().css('display','block').text(res.errors.message)
  }
  }
  })
  return false
})

$(document).on('change','input[id^=shippingaddress]',function() {
	$('.js-to-billing').css('display','none')
	$('.js-save-address').css('display','block')
})

$(document).on('change','.js-radio-change',function() {
	$('.js-to-billing').css('display','block')
	$('.js-save-address').css('display','none')
})

$(document).on('click','.js-to-billing',function() {
  let radio = $('.js-radio-change')
  let val = ''
  let order = $('.js-order-id').data('id')
  radio.each(function() {
     if ($(this).prop('checked') === true){
       val = $(this).val()
     }
  })

  $.post({
  url:'/shop/order/change-ship',
  data:{val:val,order:order},
  success:function() {
  	$('#checkShip').modal('hide')
  	$('#checkBill').modal('show')
  }
  })

})

$(document).on('change','#flexCheckDefault',function() {
  if ($(this).prop('checked') === true){
    $.post({
  url:'/shop/order/change-bill',
  data:{same:0},
  success:function(res) {
$('#customer-billing_address').val(res.bill_address).trigger('change')
$('#customer-billing_address_two').val(res.bill_address_two).trigger('change')
$('#customer-billing_city').val(res.bill_city).trigger('change')
$('#customer-billing_country').val(res.bill_country).trigger('change')
$('#customer-billing_state').val(res.bill_state).trigger('change')
$('#customer-billing_zip').val(res.bill_zip).trigger('change')
  }
  })
  }
})

$(document).on('beforeSubmit','#js-billing-address',function() {
  let form = $(this).serialize()
  $.post({
  url:'/shop/order/change-bill',
  data:{same:1,form},
  success:function() {
    $('#checkBill').modal('hide')
    $('#paypal').modal('show')
  }
  })
  return false
})


JS;
$this->registerJs($js); ?>
