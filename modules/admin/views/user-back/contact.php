<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput; ?>
<main class="page">

     <div class="container-sm py-5">
         <h1 class="h1 mb-0 p-0 product-title mb-4 text-center">Contact Us</h1>
         <h4 class="h4 mb-5 text-center d-block pr-0 ">You can contact us using the form below.</h4>
	     <?php $form = ActiveForm::begin() ?>
	     <div class="row">
                 <div class="col-md-6">
                     <div class="form-group ">
                         <label class="has-star" for="">Your First Name</label>
	                     <?= $form->field($model, 'first_name')->label(false) ?>
                     </div>

                 </div>
                 <div class="col-md-6">
                     <div class="form-group ">
                         <label class="has-star" for="">Your Last Name</label>
	
	                     <?= $form->field($model, 'last_name')->label(false) ?>
                     </div>

                 </div>
                 <div class="col-md-6">
                     <div class="form-group ">
                         <label class="has-star" for="">Your Email</label>
	
	                     <?= $form->field($model, 'email')->label(false) ?>

                     </div>

                 </div>
                 <div class="col-md-6">
                     <div class="form-group ">
                         <label class="has-star" for="">Your Mobile Phone</label>
	
	                     <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
		                     'mask'    => '(9{3}) 9{3}-9{4}',
		                     'options' =>
			                     [
				                     'placeholder' => '(123) 123-1234',
			
			                     ],
	                     ])->label(false) ?>
                     </div>

                 </div>
                 <div class="col-md-12">
                     <div class="form-group ">
                         <label class="has-star" for="">Your Message</label>
	                     <?= $form->field($model, 'text')->textarea(['rows' => 10])->label(false) ?>
                     </div>

                 </div>
                 <div class="col-md-12 d-flex justify-content-center mt-3 mb-5">
                     <button type="submit" class="btn btn-primary">Send Request</button>
                 </div>
             </div>
	     <?php ActiveForm::end() ?>
     </div>
 </main>

