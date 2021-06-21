<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

?>

<ul class="breadcrumb mt-4 ">

	<li class="breadcrumb-item"><a href="<?= Url::toRoute(['/admin/dashboard/welcome']) ?>">Welcome </a>
	</li>

</ul>
<div class="container">
	<div class="text-center mt-5 mb-5">
		<h2>PROFILE </h2>

	</div>
	<div class="row justify-content-center mb-5">

		<div class=" col-lg-8  ">
			<?php $form = ActiveForm::begin(); ?>
			<div class="row">

				<div class="offset-sm-2 col-sm-8">
					<?= $form->field($model, 'first_name') ?>
					<?= $form->field($model, 'last_name') ?>
					<?= $form->field($model, 'email') ?>
					<?= $form->field($model, 'new_pass')->label('Password')->hint('Leave blank if you dont want to change your password') ?>
					<?= $form->field($model, 'phone')->widget(MaskedInput::class, [
						'mask'    => '(9{3}) 9{3}-9{4}',
						'options' =>
						[
							'placeholder' => '(123) 123-1234',

						],
					]) ?>
					<div class="d-flex w-100">
					<?php if (Yii::$app->user->identity->role == 'user') : ?>

						<div class=" w-100 mx-auto mb-5 text-right  text-center-md">
							<?= Html::button('Add Shipping Address', [
								'class'       => 'btn    btn-outline-primary w-100   mx-md-0 mt-3',
								'data-toggle' => "modal",
								'data-target' => "#exampleModalCenter",
							]) ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="  row justify-content-center">
					<div class="col-md-6 text-right    text-center-md">
						<?= Html::submitButton('Save', ['class' => 'btn  btn-outline-primary w-100 mx-auto mx-md-0 mt-3']) ?>
					</div>

					<div class="col-md-6 text-left text-center-md ">
						<a href="<?= Url::toRoute(['/admin/dashboard/welcome']) ?>" class="btn btn-outline-primary w-100  mx-auto mx-md-0  mt-3">My Account</a>

					</div>
				</div>
				</div>




			</div> <?php ActiveForm::end(); ?>
		</div>
	</div>
</div>

<!-- Modal Ship Address-->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">My Addresses</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?php Pjax::begin(['id' => 'container-addresses']) ?>
			<div class="modal-body">

				<?php if ($addresses) : ?>
					<h5 class="modal-title mb-4 ">Select for default</h5>
					<div class="border-bottom border address-box px-3 pb-2 pt-4 mb-4">

						<?php foreach ($addresses as $address) : ?>
							<div class="form-group my-2">
								<input class="form-check-input js-radio-change" type="radio" name="radio" id="exampleRadios<?= $address->id ?>" value="<?= $address->id ?>" <?= $address->is_default ? 'checked' : '' ?>>
								<label class="form-check-label" for="exampleRadios<?= $address->id ?>">
									<?= $address->full ?>
								</label>
								<button type="button" data-id="<?= $address->id ?>" aria-label="Close" class="js-remove-address close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<hr>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php $ship = ActiveForm::begin(['id' => 'add-address']) ?>
				<div class=" ">
					<h5 class="modal-title mb-4 ">Add address</h5>
					<div class="row">
						<div class="col-md-6">
							<?= $ship->field($ship_address, 'first_name')->textInput(['value' => Yii::$app->user->identity->first_name]) ?>
						</div>
						<div class="col-md-6">
							<?= $ship->field($ship_address, 'last_name')->textInput(['value' => Yii::$app->user->identity->last_name]) ?>
						</div>
					</div>
					
					<?= $ship->field($ship_address, 'address') ?>
					<?= $ship->field($ship_address, 'address_two') ?>
					<div class="row">
						<div class="col-md-4">
							<?= $ship->field($ship_address, 'city') ?>
						</div>
						<div class="col-md-4">
							<?= $ship->field($ship_address, 'state')->dropDownList($states, ['prompt' => 'Select state...']) ?>
						</div>
						<div class="col-md-4">
							<?= $ship->field($ship_address, 'zip') ?>
						</div>
					</div>
				</div>
				<div class="modal-footer px-0">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save addresss</button>
				</div>
				<?php ActiveForm::end() ?>
			</div>
			<?php Pjax::end() ?>

		</div>
	</div>
</div>

<?php $js = <<< JS
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

$(document).on('change','.js-radio-change',function() {
  let val = $(this).val()
  $.post({
  url:'/admin/dashboard/set-address',
  data:{val:val},
  })
})

$(document).on('click','.js-remove-address',function() {
  let id = $(this).data('id')
   $.post({
  url:'/admin/dashboard/remove-address',
  data:{id:id},
  success:function() {
              $.pjax.reload({container: "#container-addresses", timeout: false})
  }
  })
})
JS;
$this->registerJs($js) ?>
