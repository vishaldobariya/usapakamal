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
use yii\widgets\Pjax;

?>
<?php ActiveForm::begin(['action' => Url::toRoute(['/shop/shop/collections']), 'method' => 'get', 'id' => 'header_search_form']) ?>
<div class="search">
	<?= AutoComplete::widget([
		'name'          => 'ProductSearch[needle]',
		'value'         => Yii::$app->request->get('ProductSearch')['needle'] ?? '',
		'options'       => ['class' => 'search-input js-search-input', 'id' => 'js-product', 'placeholder' => 'Search'],
		'clientOptions' => [
			'source' => new JsExpression('function(request, response) {
                        $.getJSON("' . Url::toRoute(['/site/search']) . '", {
                            term: $("#js-product").val(),
                        }, response);
             }'),

			'select'    => new JsExpression("function( event, ui ) {
						 if (event.keyCode == 13) {
						 	event.preventDefault();
						 	 $('#header_search_form').submit();
						 	 return false;
						 }
						 $('js-product').val(ui.item.value);
						//  $('#header_search_form').submit();
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


	<button class="search-submit js-clear-search btn-clear-search" type="button">
		<img src="/images/icon-close.svg" alt=''>

	</button>

	<button class="search-submit" type="submit">
		<img src="/images/icon-search.svg" alt=''>
	</button>
	<?//php endif; ?>

</div>

<?php ActiveForm::end() ?>

<?php $js_search = <<< JS
 $('.js-search-input').autocomplete().autocomplete('instance')._renderItem = function (ul, item) {
 				var highlightWords = $("#js-product").val().split(' ');
 				var highlightedItem = item.label;
 				let price = item.price
 				let image = item.image
 				let href = item.link
 				let id = item.id
 				// $.each(highlightWords, function (index, word) {
 				// 	if (word.length > 0) {
        //
	 			// 		highlightedItem = highlightedItem.replace(new RegExp("(" + word + ")", "gi"),
	 			// 		'%%%!!!%%%$1%%%###%%%'
	 			// 		);
 				// 	}
 				// });
        //
  			// highlightedItem = highlightedItem.replace(/%%%!!!%%%/g, '<span class="search-term-results-highlight">');
  			// highlightedItem = highlightedItem.replace(/%%%###%%%/g, '</span>');

		const it = `<div  title="` + item.label + `" class=" product-card-title pr-4 text-left d-flex align-items-center justify-content-between"><span class="d-flex align-items-center js-submit-form pr-4"><div class="search-img-container pr-2"><img  src="`+image+`" ></div>` + highlightedItem + `</span>
		<div class="d-flex flex-column justify-content-center align-items-center"><span class="ml-auto pr-4 text-bold">`+price+`</span>
		<a href="`+href+`" data-id=`+id+` class="btn btn-primary ml-0 mt-0 mr-0 js-search-btn"
		data-toggle="tooltip" data-placement="top" title="" data-original-title="Add to Cart"
		tabindex="0" data-qty="1" >SHOP NOW</a></div></div>`;
        $('#ui-id-1').css('z-index','1100')
        return $("<li>").append(it).appendTo(ul);
    };

	$(document).on('click','.js-submit-form',function() {

		$('#header_search_form').submit();
	})
$('.js-clear-search').click(function(e) {
	e.preventDefault()
  $('#js-product').val('')

  $(this).prop('type','submit')
  $(this).hide()
//   $(this).html(`<img src="/images/icon-search.svg" alt=''>`)
})

JS;
$this->registerJs($js_search); ?>