<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use trntv\filekit\widget\Upload;
use app\modules\shop\models\Product;

/* @var $this yii\web\View */
/* @var $model app\modules\shop\models\Product */
/* @var $form yii\widgets\ActiveForm */

$prod_providers = $model->storeProducts;
$value = empty($prod_providers) || $model->isNewRecord ? 'no provider yet' : Yii::$app->formatter->asDecimal((($model->price - $prod_providers[0]->price) / $prod_providers[0]->price) * 100, 2);
?>

<div class="product-form">

	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'readonly' => Yii::$app->user->identity->role != 'admin']) ?>
	
	<?php if(Yii::$app->user->identity->role == 'admin') : ?>
		<?= $form->field($model, 'image')->widget(Upload::class, [
			'url'                 => ['/storage/default/upload'],
			'uploadPath'          => 'products/',
			'sortable'            => true,
			'maxNumberOfFiles'    => 2,
			'showPreviewFilename' => false,
			'options'             => [],
		
		])->label('Image'); ?>
	<?php else : ?>
		<img width="120px" class="my-4" height="auto" src="<?= $model->thumb ?>" alt="">
	<?php endif; ?>
	
	<?= $form->field($model, 'description')->widget(TinyMce::className(), [
		'options'       => ['rows' => 16],
		'clientOptions' =>
			[
				'branding' => false,
				'readonly' => Yii::$app->user->identity->role != 'admin',
			],
	]); ?>
	<div class="row">
		<div class="col-md-6">
			<?= $form->field($model, 'price', ['options' => ['class' => 'mb-4 mt-4']])
			         ->input('number', ['min' => 0, 'step' => 0.00000001, 'readonly' => Yii::$app->user->identity->role != 'admin',])
			         ->hint('Displayed in the product
	catalog')
			         ->label('Website Price') ?>
		</div>
		<div class="col-md-6">
			<?= $form->field($model, 'provider_price', ['options' => ['class' => 'mb-4 mt-4']])->input('number', [
				'min'      => 0,
				'value'    => empty($prod_providers) ? 'no provider yet' : $prod_providers[0]->price,
				'step'     => 0.00000001,
				'readonly' => true,
			])
			         ->label(empty($prod_providers) ? 'No provider yet' : $prod_providers[0]->store->name . ' price') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'estimated_price', ['options' => ['class' => 'mb-4 mt-4']])->input('number', [
				'min'      => 0,
				'value'    => empty($prod_providers) ? 'no provider yet' : Yii::$app->formatter->asDecimal($prod_providers[0]->price / 0.7,2),
				'step'     => 0.00000001,
				'readonly' => true,
			])
			         ->label('Estimated Price')->hint('Provider Price/ 0.7') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'warning', ['options' => ['class' => 'mb-4 mt-4']])->input('number', [
				'min'   => 0,
				'value' => $value,
				'step'  => 0.01,
			])
			         ->label('Warning,%') ?>
			<div id="error-percent" style="display: <?=$value != 'no provider yet' && $value <= 30 ? 'block' : 'none'?>" class="help-block invalid-feedback">Attention! Your price is lower or equal than the
			                                                                                                                                 supplier's
			                                                                                                                           price +
			                                                                                                                      30%</div>
		</div>


		<div class="col-md-4">
			<?= $form->field($model, 'sale_price', ['options' => ['class' => 'mb-4 mt-4']])->input('number', [
				'min'      => 0,
				'step'     => 0.00000001,
				'readonly' => Yii::$app->user->identity->role != 'admin',
			])
			         ->hint('Price with discount') ?>
		</div>

		<div class="col-md-4">
			<?= $form->field($model, 'tags')->widget(Select2::class, [
				'data'          => Product::TAGS,
				'options'       => [
					'placeholder' => 'Select a tags...',
					'multiple'    => true,
					'value'       => $model->isNewRecord ? '' : explode(',', $model->tags),
				],
				'pluginOptions' => [
					'tags'               => true,
					'tokenSeparators'    => [','],
					'maximumInputLength' => 20,
				],
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'brand_id')->widget(Select2::class, [
				'data'          => $brands,
				'options'       => [
					'placeholder' => 'Select a brand...',
					'multiple'    => false,
				],
				'pluginOptions' => ['allowClear' => true,],
			]) ?>
		</div>
	</div>
	<?= $form->field($model, 'shipping')->widget(TinyMce::className(), [
		'options'       => ['rows' => 16],
		'clientOptions' =>
			[
				'branding' => false,
				'readonly' => Yii::$app->user->identity->role != 'admin',
			],
	]); ?>
	<div class="row">
		<div class="col-md-4  mb-3">
			<?= $form->field($model, 'cap')->input('number', ['min' => 0, 'step' => 1,])->label('CAP') ?>
		</div>
		<div class="col-md-4  mb-3">
			<?= $form->field($model, 'vol')->input('number', ['min' => 0, 'step' => 1,])->label('VOL,ml') ?>
		</div>
		<div class="col-md-4  mb-3">
			<?= $form->field($model, 'abv')->input('number', ['min' => 0, 'step' => 0.01,])->label('ABV,%') ?>
		</div>
		<div class="col-md-4 mb-3">
			<?= $form->field($model, 'year')->input('number', ['min' => 0, 'step' => 0.01,]) ?>
		</div>
		<div class="col-md-4 mb-3">
			<?= $form->field($model, 'age')->input('number', ['min' => 0, 'step' => 0.01,]) ?>
		</div>
		<div class="col-md-4 mb-3">
			<?= $form->field($model, 'country') ?>
		</div>
		<div class="col-md-4 mb-3">
			<?= $form->field($model, 'region') ?>
		</div>

		<div class="col-md-4">
			<?= $form->field($model, 'category_id')->widget(Select2::class, [
				'data'          => $cats,
				'options'       => [
					'placeholder' => 'Select a category...',
					'multiple'    => false,
					'id'          => 'cat-id',
				],
				'pluginOptions' => [
					'allowClear' => true,
				],
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'sub_category_id')->widget(DepDrop::classname(), [
				'options'       => ['id' => 'subcat-id'],
				'data'          => $sub_cats,
				'pluginOptions' => [
					'depends'     => ['cat-id'],
					'placeholder' => 'Select...',
					'url'         => Url::toRoute(['/shop/product/subcat']),
				],
			])->label('Sub Category') ?>
		</div>
		<div class="col-md-4 mt-3">
			<div class="checkbox-wrap">
				<?= $form->field($model, 'available')->checkbox()->label(false) ?>
				<?= $form->field($model, 'featured_brand')->checkbox()->label(false) ?>
				<?= $form->field($model, 'special_offers')->checkbox()->label(false) ?>
				<?= $form->field($model, 'visible')->checkbox()->label(false) ?>
			</div>

		</div>

	</div>
	
	<?= $form->field($model, 'sku') ?>
	
	<?= $form->field($model, 'seo_title') ?>
	<?= $form->field($model, 'seo_description')->textarea(['rows' => 4]) ?>
	<?= $form->field($model, 'seo_keywords')->textarea(['rows' => 4])->hint('Separate with comma') ?>
	<div class="form-group mt-3">
		<?= Html::submitButton('Save', ['class' => 'btn btn-success mt-4']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>
<?php $js = <<< JS

$(document).on('keyup','#product-price',function() {
let price = Number($(this).val())
let provider = Number($('#product-provider_price').val())
let percent = (price - provider)/provider
let per = percent.toFixed(2)
let out = per*100
$('#product-warning').val(out.toFixed(2))
if(per*100 <= 30){
  $('#error-percent').css('display','block')
}else{
    $('#error-percent').css('display','none')

}
})

$(document).on('keyup','#product-warning',function() {
let percent = Number($(this).val())
let provider = Number($('#product-provider_price').val())
let price = (percent*provider)/100 + provider
$('#product-price').val(price.toFixed(2))
if(percent <= 30){
  $('#error-percent').css('display','block')
}else{
    $('#error-percent').css('display','none')

}
})

$(document).on('ready',function() {
  select2.trigger('change')
})

JS;
$this->registerJs($js) ?>
