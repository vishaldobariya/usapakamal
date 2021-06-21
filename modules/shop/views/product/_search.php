<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use kartik\form\ActiveForm;

?>
<?php ActiveForm::begin(['action' => Url::toRoute(['/shop/product/index']), 'method' => 'get', 'id' => 'search_form_product', 'options' => ['style' => '    flex-grow: 1;
    max-width: 60%; ']]) ?>


<div class="grid-form-group highlight-addon field-productsearch-search">


	<div class="input-group">
		<?= AutoComplete::widget([
			'name'          => 'ProductSearch[search]',
			'value'         => Yii::$app->request->get('ProductSearch')['search'] ?? '',
			'options'       => ['class' => 'grid-search form-control js-search-input', 'id' => 'js-input-product', 'placeholder' => 'Search'],
			'clientOptions' => [
				'source' => new JsExpression('function(request, response) {
                        $.getJSON("' . Url::toRoute(['/site/search']) . '", {
                            term: $("#js-input-product").val(),
                        }, response);
             }'),

				'select'    => new JsExpression("function( event, ui ) {
						 if (event.keyCode == 13) {
						 	event.preventDefault();
						 	 $('#search_form_product').submit();
						 	 return false;
						 }

						 $('js-input-product').val(ui.item.value);
						 $('#search_form_product').submit();
			       //location.href = '/marketplace/shop/result?ProductSearch%5Bneedle%5D=' + escape(ui.item.value)
			     }"),
				'open'      => new JsExpression("function( event, ui ) {
			    let inputWidth = $(event.target).outerWidth();
			       $('.ui-autocomplete').css({
			          'max-width': inputWidth + 'px'
			       })
       }"),
				'autoFocus' => true,
				'minLength' => 2,
			],
		]) ?>
		<div class="input-group-append">
			<button type="submit" class="btn btn-search"><svg class="svg-inline--fa fa-search fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
					<path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z">
					</path>
				</svg>
				<!-- <i class="fas fa-search"></i> --></button>
		</div>
	</div>
</div>

<?php ActiveForm::end() ?>

<?php $js_search = <<< JS
 $('.js-search-input').autocomplete().autocomplete('instance')._renderItem = function (ul, item) {
 				var highlightWords = $("#js-input-product").val().split(' ');
 				var highlightedItem = item.label;
 				$.each(highlightWords, function (index, word) {
 					if (word.length > 0) {

	 					highlightedItem = highlightedItem.replace(new RegExp("(" + word + ")", "gi"),
	 					'%%%!!!%%%$1%%%###%%%'
	 					);
 					}
 				});

  			highlightedItem = highlightedItem.replace(/%%%!!!%%%/g, '<span class="search-term-results-highlight">');
  			highlightedItem = highlightedItem.replace(/%%%###%%%/g, '</span>');

        const it = '<div title="' + item.label + '" class="product-card-title pr-2">' + highlightedItem + '</div>';
        $('#ui-id-1').css('z-index','1100')
        return $("<li>").append(it).appendTo(ul);
    };
$('.js-clear-search').click(function(e) {
	e.preventDefault()

  $('#js-input-product').val('')
  $(this).prop('type','submit')
  $(this).html(`<img src="/images/icon-search.svg" alt=''>`)
})

JS;
$this->registerJs($js_search); ?>
