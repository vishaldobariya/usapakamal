<?php

use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use app\components\AdminGrid;
use kartik\grid\ActionColumn;
use app\modules\shop\models\Product;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\shop\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
$sort = Yii::$app->request->get('sort');
$class = Yii::$app->session->get('hide_image') ? 'btn-danger' : 'btn-success';
$btnName = Yii::$app->session->get('hide_image') ? 'Show' : 'Hide';
$labels = $model->attributeLabels();
$keys = array_keys($labels);
$checked = Yii::$app->session->get('checked') ?? [];
?>

<button class="btn <?= $class ?> mb-3" id="js-hide-images"><?= $btnName ?> Images</button>
<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModalCenter">Import
	CSV</button>
<a href="<?= Url::toRoute(['/shop/product/export']) ?>" class="btn btn-success mb-3">Export CSV</a>
<a href="<?= Url::toRoute(['/shop/product/alert']) ?>" class="btn btn-danger mb-3">Show Alerts</a>
<div class="store-index">
	<div class="row mb-3">
		<?php foreach ($keys as $key) : ?>
			<div class="col-md-2">
				<div class="form-check">
					<input type="checkbox" class="form-check-input js-input-check" name="attr_checked[]" <?= in_array($key, $checked) ? 'checked' : '' ?> value="<?= $key ?>" id="exampleCheck1<?= $key ?>">
					<label class="form-check-label" for="exampleCheck1<?= $key ?>"><?= $labels[$key] ?></label>
				</div>
			</div>
		<?php endforeach; ?>
	</div>


	<?= AdminGrid::widget([
		'title'                => 'Products',
		'extraSearch'          => $this->render('@app/modules/shop/views/product/_search', ['model' => $searchModel]),
		'dataProvider'         => $dataProvider,
		'filterModel'          => $searchModel,
		'panelHeadingTemplate' => '<div class="d-flex justify-content-between  flex-wrap align-items-center">
    <div class="d-flex justify-content-start align-items-center">{createButton}{gridTitle}</div>
    <div class="d-flex justify-content-end align-items-start flex-grow-1">{extraSearch}<span class="ml-3"></span><a class="btn btn-link" href="' . Url::toRoute(['/shop/product/index']) . '">Clean Filters</a></div>
</div>',
		'tableOptions'         => ['class' => 'text-normal'],
		'columns'              => [
			//[
			//	'attribute' => 'id',
			//	'visible'   => in_array('id', $checked),
			//
			//],
			[
				'header'  => '<a href="' . Url::toRoute(['/shop/product/index', 'image_sort' => $sort == 'ASC' ? '-DESC' : 'ASC']) . '">Image</a>',
				'label'   => 'Image',
				'visible' => Yii::$app->session->has('hide_image') ? false : true,
				'value'   => function ($model) {
					return '<img width="" height="auto" class="table-img img-responsive" src="' . $model->thumb . '">';
				},
				'format'  => 'raw',
			],
			[
				'attribute' => 'name',
				'filter'    => false,
				'visible'   => in_array('name', $checked),
			],
			[
				'attribute' => 'price',
				'label'     => 'Website Price',
				'format'    => 'raw',
				'value'     => function ($model) {
					$html = '';
					$html .= Yii::$app->formatter->asCurrency($model->getPrice()) . '<br>';
					if (!empty($model->storeProducts)) {
						$percent = ($model->getPrice() - $model->storeProducts[0]->price) / $model->storeProducts[0]->price;
						$display = $percent * 100 > 30 ? 'display:none' : 'display:block';
						$html .= '<progress id="progress" style="width:100%" max="' . $model->getPrice() . '" title="' . $model->storeProducts[0]->store->name . '" value="' .
							$model->storeProducts[0]->price . '"></progress>
		<div class="help-block invalid-feedback" style="' . $display . '">Attention! Your price is lower or equal than the supplier\'s price + 30%</div>';
					}

					return $html;
				},
				'visible'   => in_array('price', $checked),
			],
			[
				'label'  => 'Provider Price',
				'filter' => false,
				'value'  => function ($model) {
					if (!empty($model->storeProducts)) {
						return Yii::$app->formatter->asCurrency($model->storeProducts[0]->price);
					} else {
						return 'No provider yet';
					}
				},
			],

			//[
			//	'label'     => 'Providers',
			//	'attribute' => 'catalogs',
			//	'visible'   => in_array('catalogs', $checked),
			//	'value'     => function($model) use ($catalogs)
			//	{
			//		$cat = explode(',', $model->catalogs);
			//		$str = [];
			//		foreach($cat as $ct) {
			//			$str[] = $catalogs[$ct] ?? '';
			//		}
			//
			//		return implode(', ', $str);
			//	},
			//	'filter'    => $catalogs,
			//],
			//'price_min:currency',
			[
				'attribute'           => 'category_id',
				'visible'             => in_array('category_id', $checked),
				'label'               => 'Category',
				'value'               => function ($model) {
					return $model->category->name ?? '';
				},
				'filter'              => $categories,
				'filterType'          => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
					'options'       => [
						'prompt' => '',
						// 'style'  => 'width: 200px;',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			[

				'attribute'           => 'brand_id',
				'visible'             => in_array('brand_id', $checked),
				'label'               => 'Brand',
				'value'               => function ($model) {
					return $model->brand->name ?? '';
				},
				'filter'              => $brands,
				'filterType'          => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
					'options'       => [
						'prompt' => '',
						// 'style'  => 'width: 200px;',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],

			],
			[
				'attribute'           => 'sku',
				'visible'             => in_array('sku', $checked),
				'filter'              => $sku,
				'filterType'          => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
					'options'       => [
						'prompt' => '',
						// 'style'  => 'width: 200px;',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			[
				'attribute' => 'available',
				'visible'   => in_array('available', $checked),
				'filter'    => [0 => 'Not Available', 1 => 'Available'],
				'value'     => function ($model) {
					return $model->available ? '<svg class="svg-inline--fa fa-check fa-w-16 text-success" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>' : '<svg class="svg-inline--fa fa-times fa-w-11 text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" data-fa-i2svg=""><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>';
				},
				'format'    => 'raw',
			],
			[
				'attribute' => 'visible',
				'visible'   => in_array('visible', $checked),
				'filter'    => [0 => 'Hidden', 1 => 'Visible'],
				'value'     => function ($model) {
					return $model->visible ? '<svg class="svg-inline--fa fa-check fa-w-16 text-success" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>' : '<svg class="svg-inline--fa fa-times fa-w-11 text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" data-fa-i2svg=""><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>';
				},
				'format'    => 'raw',
			],
			[
				'contentOptions' => [
					'class' => 'text-center',
				],
				'attribute'      => 'featured_brand',
				'visible'        => in_array('featured_brand', $checked),
				'value'          => function ($model) {
					$text = $model->featured_brand && $model->featured_brand != null ? 'Yes' : 'No';
					$class = $text == 'Yes' ? 'btn-success' : 'btn-primary';

					return '<button data-id="' . $model->id . '" class="btn ' . $class . ' js-featured_brand">' . $text . '</button>';
				},
				'format'         => 'raw',
				'filter'         => [0 => 'No', 1 => 'Yes'],
			],
			[
				'contentOptions' => [
					'class' => 'text-center',
				],
				'attribute'      => 'special_offers',
				'visible'        => in_array('special_offers', $checked),
				'value'          => function ($model) {
					$text = $model->special_offers && $model->special_offers != null ? 'Yes' : 'No';
					$class = $text == 'Yes' ? 'btn-success' : 'btn-primary';

					return '<button data-id="' . $model->id . '" class="btn ' . $class . ' js-spec-offer">' . $text . '</button>';
				},
				'format'         => 'raw',
				'filter'         => [0 => 'No', 1 => 'Yes'],
			],
			[
				'attribute' => 'description',
				'visible'   => in_array('description', $checked),
			],
			[
				'attribute' => 'slug',
				'visible'   => in_array('slug', $checked),

			],
			[
				'attribute' => 'cap',
				'visible'   => in_array('cap', $checked),

			],
			[
				'attribute' => 'vol',
				'visible'   => in_array('vol', $checked),

			],
			[
				'attribute' => 'abv',
				'visible'   => in_array('abv', $checked),

			],
			[
				'attribute' => 'shipping',
				'visible'   => in_array('shipping', $checked),

			],
			[
				'attribute' => 'sale_price',
				'visible'   => in_array('sale_price', $checked),
				'value'     => function ($model) {
					return Yii::$app->formatter->asCurrency($model->sale_price);
				},

			],
			[
				'attribute' => 'price_min',
				'visible'   => in_array('price_min', $checked),
				'value'     => function ($model) {
					return Yii::$app->formatter->asCurrency($model->price_min);
				},

			],
			[
				'attribute' => 'tags',
				'visible'   => in_array('tags', $checked),
				'format' => 'raw',
				'value'     => function ($model) {
					$res = '';
					if ($model->tags != '') {
						$tags = explode(',', $model->tags);
						$res = '';
						foreach ($tags as $tag) {
							$res .= '<span class="tag label label-warning mb-1">' . Product::TAGS[$tag] . '</span>';
						}
					}

					return $res;
				},
			],
			[
				'attribute' => 'age',
				'visible'   => in_array('age', $checked),

			],
			[
				'attribute' => 'seo_title',
				'visible'   => in_array('seo_title', $checked),

			],
			[
				'attribute' => 'seo_keywords',
				'visible'   => in_array('seo_keywords', $checked),

			],
			[
				'attribute' => 'seo_description',
				'visible'   => in_array('seo_description', $checked),

			],
			[
				'class'          => ActionColumn::class,
				'header'         => 'Controls',
				'width'          => false,
				'template'       => '<div class="actions">{history}<span class="ml-5"></span>{update}<span class="ml-5"></span>{delete}</div>',
				'visibleButtons' => [
					'delete' => Yii::$app->user->identity->role == 'admin',

				],
				'buttons'        => [
					'history' => function ($url, $model) {
						return '<a data-target="#history" class="js-history" data-id="' . $model->id . '" href="#"><i class="fas fa-history"></i></a>';
					},
				],
			],
		],
	]); ?>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Import CSV</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?php $form = ActiveForm::begin([
				'id'      => 'js-form-csv',
				'method'  => 'post',
				'options' => ['enctype' => 'multipart/form-data'],
				'action'  => Url::toRoute(['/shop/product/import']),
			]) ?>
			<div class="modal-body px-4 py-0">
				<p class="text-light text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
				</p>
				<div class="d-flex my-4 align-items-center">
					<?= $form->field($csv, 'csv')->fileInput(['id' => 'csv-upload', 'class' => 'd-none csv-upload'])->label(false) ?>

					<!--	          <input id="csv-upload" type="file" name="csv" class="csv-upload">-->
					<button type="button" class="add-btn js-csv-upload mr-3">
						choose file
					</button>
					<span class="js-file-name">No file chosen</span>
				</div>
				<p class="text-sm mb-3">Download a <a href="<?= Url::toRoute(['/shop/product/export', 'count' => 8]) ?>" class="green-link">sample CSV template</a> to see an example of the preferred format.</p>
			</div>
			<div class="modal-footer px-4">
				<button type="button" id="js-upload-button" disabled class="btn btn-success">Upload file</button>
			</div>
			<?php ActiveForm::end() ?>
		</div>
	</div>
</div>

<div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTitle">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="textMessage">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">Ok</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal History -->
<div class="modal fade" id="history" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">History</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table">
					<thead>
						<tr>
							<th scope="col">Updated at</th>
							<th scope="col">Price</th>
						</tr>
					</thead>
					<tbody class="res-container">
						<tr>
							<td>2020</td>
							<td>23</td>
						</tr>
					</tbody>
				</table>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php $js = <<< JS
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
       form.submit()
    })




$(document).on('click','#js-hide-images',function() {
  if($(this).hasClass('btn-danger')) {
    $(this).removeClass('btn-danger')
    $(this).addClass('btn-success')
    $(this).text('Hide Images')
  } else {
    $(this).removeClass('btn-success')
    $(this).addClass('btn-danger')
    $(this).text('Show Images')
  }

  $.post({
  url:'/shop/product/hide-image',
  success:function() {
  	$.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})


$(document).on('click','.js-featured_brand',function() {
  let id = $(this).data('id')
  $.post({
  url:'/shop/product/featured',
  data:{id:id},
  success:function() {
      	$.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})

$(document).on('click','.js-spec-offer',function() {
  let id = $(this).data('id')
  $.post({
  url:'/shop/product/special',
  data:{id:id},
  success:function() {
      	$.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})
$(document).on('click','.js-history',function(e) {
  e.preventDefault()
  let val = $(this).data('id')
  $.post({
  url:'/shop/product/get-history',
  data:{val:val},
  success:function(res) {
	  let layout = '';
	  let color = '';
	  for (let i = 0; i < res.length; i++) {
	    i === (res.length-1) ? color='green' : ''
	    layout += '<tr><td>' + res[i].updated_at + '</td><td style="color:'+color+'">' + res[i].price + '</td></tr>';
	  }
	  $('.res-container').html(layout);
	  $('#history').modal('show');
  }
  })

})

$(document).on('change','.js-input-check',function() {
  let val = $(this).val()
  $.post({
  url:'/shop/product/add-checked',
  data:{val:val},
  success:function() {
    $.pjax.reload({container: "#w0-pjax", timeout: false})
  }
  })
})
JS;
$this->registerJs($js)
?>