<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

?>
<main class="page products">
    <div class="container">
        <div class="row catalogue-inner">
            <div class="col-sm-6 col-lg-4   catalogue-item">
            </div>
            <div class="col-sm-6 col-lg-4   catalogue-item">
               <div class="py-5 main-screen align-items-stretch">
	<?php $form = ActiveForm::begin() ?>
	
	<?= $form->field($model, 'email')->input('text',['readonly' => true]) ?>
	<?= $form->field($model, 'first_name') ?>
	<?= $form->field($model, 'last_name') ?>
	<?= $form->field($model, 'store_name') ?>
	<?= $form->field($model, 'zip')->widget(MaskedInput::class, [
		'mask'    => '9{5}',
		'options' =>
			[
				'placeholder' => '11111',
			
			],
	]) ?>
	<?= $form->field($model, 'phone')->widget(MaskedInput::class, [
		'mask'    => '(9{3}) 9{3}-9{4}',
		'options' =>
			[
				'placeholder' => '(123) 123-1234',
			
			],
	]) ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'confirm_password')->passwordInput() ?>
	
	
	<?php Html::button('Sign Up', ['class' => 'btn btn-primary','data-toggle'=>"modal", 'data-target'=>"#updateCatalog"]) ?>
	<?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary']) ?>
	
			</div>
			</div>
        </div>
    </div>
</main>

<!-- Update Catalog Modal -->
<div class="modal fade" id="updateCatalog" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Your Products</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
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
	     
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary js-form-update">Sign Up</button>
      </div>
    </div>
  </div>
</div>
<?php ActiveForm::end() ?>
