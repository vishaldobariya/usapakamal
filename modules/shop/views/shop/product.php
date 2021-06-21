<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use app\components\Upload;
use kartik\form\ActiveForm;
use app\modules\shop\models\Product;

/**
 * @var $product Product
 */

Yii::$app->opengraph->title = $product->seo_title != '' ? $product->seo_title : $product->name . ': Royal Batch';
Yii::$app->opengraph->description = trim(strip_tags($product->seo_description != '' ? $product->seo_description : $product->description));
Yii::$app->opengraph->type = 'product';
Yii::$app->opengraph->image = $product->thumb;

?>
<main class="page  ">
  <section class="container p-0">

    <ul class="breadcrumb ">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
	    <?php if($product->category != null) : ?>
		    <li class="breadcrumb-item"><a href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[category_id]' => [$product->category->id]]) ?>"><?= $product->category->name ?></a>
        </li>
	    <?php endif; ?>
	    <li class="breadcrumb-item active" aria-current="page"><?= $product->name ?></li>

    </ul>


  </section>
	<?php ActiveForm::begin(['id' => 'js-form-add-cart', 'options' => ['class' => 'product-cart mb-4 d-flex']]) ?>
	<div class="container container-sm">
    <div class="row  product-item mt-4 js-product-card">
      <div class="modal fade" id="js-engraving" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
          <div class="modal-content bg-dark" style="border: none; ">
            <div class="modal-body">
              <h3> <strong class="card-link mt-auto" style="color: #ae894f"> Your Engraving
                </strong></h3>
              <p class="text-white">Confirm that personalization you entered is correct</p>
              <div class="p-4 w-75 mx-auto border border-primary">
                <small class="text-primary text-left d-block w-100">Line 1</small>
                <p class="js-line1 text-white text-left  "></p>
                <small class="text-primary text-left d-block w-100">Line 2</small>
                <p class="js-line2 text-white  text-left "></p>
                <small class="text-primary text-left d-block w-100">Line 3</small>
                <p class="js-line3  text-white  text-left  "></p>

                <p class="js-line4  text-white  text-left  "></p>
              </div>
              <div class="d-flex justify-content-center">
                <a href="#" class="js-confirm-message btn m-3">Confirm</a>
                <a href="#" class="js-buy-now-confirm btn m-3" style="display: none">Confirm</a>
                <a href="#" class="js-edit-message btn m-3">Edit</a>
              </div>
              <p class="text-white text-center">
                Please allow up to 10 business days for custom engraving and order processing prior to shipment. Orders will ship as soon as possible after engraving is completed. Location of engraving on the bottle is at the discretion of our engravers and cannot be modified.
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-lg-6 ">
        <div class="product-imgs">
          <div class="js-product-slider position-relative">
            <div class="product-img  zoom zoomimages">
              <img id="thumb" src="<?= $product->thumb ?>" width="1100" class="js-product-img" alt="/">
	            <!-- <img id="thumb" src="/images/prod.jpg" alt="/"> -->
            </div>
            <div class="product-img-icon">
              <img src="/images/icon-search.svg" alt="">
            </div>
          </div>
	        <!-- <div class="product-imgs-zoom" id="product-imgs-zoom" style=" "></div> -->
        </div>
      </div>

      <div class="col-sm-12 col-lg-6  product-description  py-0">
        <h1 class="product-title p-0 position-static"><?= $product->name ?></h1>
        <div class="pruduct-price h3 mt-3 mb-3 text-primary ">
          <?php if($product->isSale()) : ?>
	          <div class="product-card-oldprice">
              <span><?= Yii::$app->formatter->asCurrency($product->price) ?></span>
            </div>
          <?php endif; ?>
	        $<span id="js-price" data-price="<?= $product->getPrice() ?>"><?= Yii::$app->formatter->asDecimal($product->getPrice(), 2) ?></span>
        </div>
	      <!-- <div class="product-rating-stars">
						  <i class="fa fa-star"></i>
						  <i class="fa fa-star"></i>
						  <i class="fa fa-star"></i>
						  <i class="fa fa-star-o"></i>
						  <i class="fa fa-star-o"></i>
					  </div> -->
	      <?php if($product->category) : ?>
		      <div class="product-meta mb-2">
            <div class="product-meta-row"><span class="product-meta-name">CATEGORY:</span> <a
		            href="<?= Url::toRoute(['/shop/shop/collections', 'ProductSearch[category_id]' => [$product->category->id]]) ?>" class="product-meta-text px-2"
		            rel="tag"><?= $product->category->name ?> </a></div>
          </div>
	      <?php endif; ?>
	      <?php if($product->subCategory) : ?>
		      <div class="product-meta mb-2">
            <div class="product-meta-row"><span class="product-meta-name">SUB-CATEGORY:</span> <a href="<?= Url::toRoute([
		            '/shop/shop/collections',
		            'ProductSearch[sub_category_id]' =>
			            [$product->subCategory->id],
	            ]) ?>" class="product-meta-text px-2" rel="tag"><?= $product->subCategory->name ?> </a></div>
          </div>
	      <?php endif; ?>
	      <div class="product-meta mb-2">
          <div class="product-meta-row"><span class="product-meta-name">SIZE:</span> <a href="#" class="product-meta-text px-2" rel="tag"><?= $product->vol ?>ml </a></div>


        </div>
	      <?php if($product->abv != null && $product->abv < 100) : ?>
		      <div class="product-meta mb-2">
            <div class="product-meta-row"><span class="product-meta-name">PROOF/ABV:</span> <a href="#" class="product-meta-text px-2" rel="tag">
                <?= ($product->abv * 2) . '/' . $product->abv ?>
		            %
              </a></div>


          </div>
	      <?php endif; ?>
	      <!-- <div class="product-meta mb-2">
						  <div class="product-meta-row">  <span
								 class="product-meta-name " rel="tag"><span class="text-primary">15</span> IN STOCK</span></div>
								 </div> -->
	
	
	      <?php if($product->isAvailableWithEngraving()) : ?>
		      <div class="form-check form-switch pl-0 mt-3">
            <div class="d-flex align-items-flex-center mb-3">
              <input class="form-check-input d-none" type="checkbox" name="engrave_front" alt="<?= \Yii::$app->settings->front_engraving ?>" id="js-engrave-front">

              <label class="form-check-label" for="js-engrave-front">
                <div class="custom-checkbox"></div> ADD CUSTOM ENGRAVING +<?= Yii::$app->formatter->asCurrency(\Yii::$app->settings->front_engraving) ?>
              </label>
            </div>
          </div>
		      <div id="js-block-front-engrave" style="display: none;">


            <div class="row">
              <div class="col-md-6">
                <p><strong>Option 1</strong></p>
                <div class="js-engraving-option"> <small>Line 1</small>
                  <input type="text" class="form-control mb-3" data-line="1" name="front[front_line_1]" placeholder="Line 1">

 <small>Line 2</small>
                  <input type="text" class="form-control mb-3" data-line="2" name="front[front_line_2]" placeholder="Line 2">

  <small>Line 3</small>
                  <input type="text" class="form-control mb-3" data-line="3" name="front[front_line_3]" placeholder="Line 3">

                </div>
              </div>
              <div class="col-md-6">
                <p><strong>Option 2</strong></p>
	              <?= Upload::widget([
		              'name'                => 'front[front_image]',
		              'options'             => [
			              'data-id' => '232',
		              ],
		              'files'               => [],
		              'hiddenInputId'       => true,
		              'url'                 => ['/storage/default/upload'],
		              'uploadPath'          => 'engraving/',
		              'sortable'            => true,
		              'maxNumberOfFiles'    => 1,
		              'showPreviewFilename' => false,
		              'acceptFileTypes'     => new JsExpression('/(\.|\/)(png)$/i'),
	              ]);
	
	              ?>
	              <p>Upload your logo file (.png). Please note that engraving logo should be in two colors only (Black and white).</p>
              </div>

            </div>
          </div>
	
	      <?php endif; ?>

	      <div class="d-flex justify-content-start flex-wrap">
          <div class="product-cart-buttons quantity mr-sm-1  mb-sm-0 mb-3 ">
            <input type="number" id="js-input-product" class="quantity-input p-2" data-step="1" data-min="1" data-max="" name="qty" value="1" min="1" title="Qty" size="4" inputmode="numeric">
            <input type="hidden" class="quantity-input p-2" min="1" data-step="1" data-min="1" data-max="" name="id" value="<?= $product->id ?>" title="Qty" size="4" inputmode="numeric">
          </div>
          <a href="#" style="height: auto" id="<?= $product->isSold() ? '' : 'js-button-add-to-cart' ?>" class="btn btn-primary mx-0 mx-sm-2 js-add-btn  mb-sm-0 mb-3 w-100-sm">
            <?= $product->isSold() ? 'Sold Out' : 'Add to Cart'
            ?></a>
		      <?php if(!$product->isSold()) : ?>
			      <a href="<?= Url::toRoute(['/shop/cart/information']) ?>" style="height: auto" data-qty="1" data-id="<?= $product->id ?>" class="btn btn-white mx-0 mx-sm-2 js-add-to-cart-buy-now  mb-sm-0
            mb-3 w-100-sm">
              BUY NOW</a>
		      <?php endif; ?>

        </div>



        <div class="product-tabs" id="js-info">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">DESCRIPTION</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                Additional information </a>
            </li>

            <li class="nav-item">
              <!-- <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                                Reviews (1) </a> -->
            </li>

          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active text-sm" id="home" role="tabpanel" aria-labelledby="home-tab">
              <h5><?= $product->name ?></h5>
	            <?= $product->description ?>

            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="table-responsive">
                <table class="table w-100 table-striped">
                  <tbody>
                    <tr>
                      <td>PRODUCT NAME</td>
                      <td><?= $product->name ?></td>
                    </tr>
                    <tr>
                      <td>PRODUCT CODE</td>
                      <td><?= $product->sku ?></td>
                    </tr>
                    <!--                                        <tr>-->
                    <!--                                            <td>SIZE</td>-->
                    <!--                                            <td>500ml</td>-->
                    <!--                                        </tr>-->
                    <tr>
                      <td>CATEGORY</td>
                      <td><?= $product->category->name ?? '' ?></td>
                    </tr>

                  </tbody>
                </table>


              </div>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
              <h3 class="text-primary h6 my-4">1 REVIEW</h3>

              <ol class="comment-list">
                <li class="comment-list-item">

                  <div class="comment-list-text">
                    <div class="comment-list-rating" role="img" aria-label="Rated 3 out of 5">
                      <span class="comment-list-rating-stars">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star-o"></i>
                      </span>
                    </div>
                    <p class="comment-list-meta">
                      <strong class="comment-list-author">
                        Michael Elliott
                      </strong>
                      <span class="">–</span>
                      <time class="comment-list-date" datetime="2019-09-03T09:58:48+00:00">September 3, 2019</time>
                    </p>
                    <div class="comment-list-descr text-sm">
                      <p>Nulla tempus massa vitae tellus malesuada sollicitudin. Sed euismod
                        porttitor lobortis. Aenean eget libero sit amet arcu facilisis dapibus
                        ut in nibh. Nam at leo nisi. In eu sodales elit, sed dapibus elit.</p>
                    </div>
                  </div>

                </li>
              </ol>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">

        <h2 class="h1 text-center mt-5 mb-4">YOU MAY ALSO LIKE</h2>


      </div>
	    <?php foreach($sames as $same) : ?>
		    <?php
		
		    /**
		     * @var $same Product
		     */
		    ?>
		    <div class="col-lg-3 col-sm-6 col-6 collection-item">
          <div class="product-card js-product-card  <? if($same->isSold()) {
	          echo 'product-card-sold';
          }; ?>">
            <div class="product-card-img">
              <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $same->slug]) ?>" class="product-card-img-link"><img class="img js-product-img" loading="lazy" src="<?= $same->thumb ?>"
                                                                                                                              alt=""></a>

              <div class="product-action">

                <div class=" product-action-logo">
                  <img src="/images/logo-sm.svg" alt="">
                </div>
                <div class="product-action-tags">



                  <?php if($same->isSold()) : ?>
	                  <div class="product-action-tag product-action-sold">

                    </div>

                  <?php endif; ?>
	
	                <?php if($same->isAvailableWithEngraving()) : ?>

		                <div class="product-action-tag product-action-custom">

                    </div>
	
	                <?php endif; ?>
	                <?php if($same->isSale()) : ?>
		                <div class="product-action-tag product-action-sale">

                    </div>
	
	                <?php endif; ?>
	                <?php if($same->isSpecialPromotion()) : ?>
		                <div class="product-action-tag product-action-special">

                    </div>
	
	                <?php endif; ?>
	                <?php if($same->isLimitedEdition()) : ?>
		                <div class="product-action-tag product-action-limited">

                    </div>
	                <?php endif; ?>
                </div>
              </div>

            </div>
            <div class="product-card-descr">
              <h3 class="product-card-title product-card-title-sm  "><?= ($same->brand->name ?? '') ?></h3>

              <a class="product-card-title-link" href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $same->slug]) ?>">
                <h3 class="product-card-title fixed"><?= $same->name ?></h3>
              </a>
	            <!-- <div class="product-card-text">
                            <? //= $same->description ?>
                        </div> -->
              <div class=" mt-auto">
                <div class="product-card-tags">
                  <?= ($same->category->name ?? '') ?>
                </div>
              </div>
              <div class="product-card-footer">

                <div class="product-card-price">
                  <?php if($same->isSale()) : ?>
	                  <div class="product-card-oldprice">
                      <span><?= Yii::$app->formatter->asCurrency($same->price) ?></span>
                    </div>
                  <?php endif; ?>
	                <span><?= Yii::$app->formatter->asCurrency($same->getPrice()) ?></span>

                </div>

                <div class="text-center">
                  <a href="#" class="product-card-cart js-add-to-cart" data-qty="1" data-id="<?= $same->id ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add to Cart"
                     tabindex="0">

                    <!-- <img src="/images/cart.svg" alt=""> -->
                  </a>
                  <a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $same->slug]) ?>" class="btn btn-primary">SHOP NOW</a>
                  <span class="btn btn-primary btn-sold">

                    SOLD OUT
                  </span>
                </div>
              </div>
            </div>

          </div>

        </div>
	    <?php endforeach; ?>



    </div>
  </div>
	<?php ActiveForm::end() ?>
</main>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php $js = <<< JS


let showModalEngraving = false;

$(document).on('blur','.js-engraving-option input',function() {
  const text = $(this).val();
  const line = $(this).data('line');
  $('.js-line'+line).text(text)
  if(text){
    $(this).addClass('is-valid')
  }else{
    $(this).removeClass('is-valid')
  }

})
$(document).on('click', '.upload-kit-item .remove',function(e) {
    $('#js-block-front-engrave').removeClass('is-valid')
})
$(document).on('change', 'input[name="_fileinput_w0"]',function(e) {

  const text = 'Upload image: ' + e.target.files[0].name;

  $('#js-block-front-engrave').addClass('is-valid')

  $('.js-line4').text(text)


})
$(document).on('click', '.upload-kit-item .remove',function(e) {

const text = '';
$('.js-line4').text(text)
})
$(document).on('change','#js-engrave-front',function() {
  let sum = $(this).attr('alt')
  let old_price = $('#js-price').data('price')
  let amount = 0
  if($(this).prop('checked')){
    // $('.js-add-btn').attr('id', '')
    $('#js-block-front-engrave').css('display','block')
    amount = Number(old_price) + Number(sum)
    //$('#js-price').text(amount.toFixed(2))
    $('#js-form-add-cart').append('<input type="hidden" id="engraving_front" name="engraving_front" value="1">')
  }else{
    // $('.js-add-btn').attr('id', 'js-button-add-to-cart')
    //$('#js-price').text($('#js-price').data('price').toFixed(2))
    $('#js-block-front-engrave').css('display','none')
	$('#engraving_front').remove()
  }
})

$(document).on('ready', function() {
    $(document).on('change','#w0',function() {

})
})

$(document).on('change','#js-engrave-back',function() {
let sum = $(this).attr('alt')
let old_price =  $('#js-price').text()
let amount = 0
  if($(this).prop('checked')){

    amount = Number(old_price) + Number(sum)
    //$('#js-price').text(amount.toFixed(2))
    $('#js-block-back-engrave').css('display','block')
        $('#js-form-add-cart').append('<input type="hidden" id="engraving_back" name="engraving_back" value="1">')

  }else{

    old_price =  $('#js-price').text()
     amount = Number(old_price) - Number(sum)
    //$('#js-price').text(amount.toFixed(2))
        $('#js-block-back-engrave').css('display','none')
		$('#engraving_back').remove()
  }
})

$(document).on('click','.js-add-to-cart-buy-now',function(e) {
  e.preventDefault()
 
  let eng = $('#js-engrave-front')
 if(eng.prop('checked')){
if ($('#js-block-front-engrave input.is-valid').length) {
        $('#js-engraving')
          .modal('show');
      } else if ($('#js-block-front-engrave.is-valid').length) {
        $('#js-engraving')
          .modal('show');
      } else {
        swal({
          title: 'Attention!',
          text:
            'You have not added engraving! Cancel engraving if you change your mind.',
          icon: 'error',
          button: true,
        });
      }
$('.js-confirm-message').css('display','none')
 $('.js-buy-now-confirm').css('display','block')
 }else{
   buyNow()
 }
})

function buyNow(){
  const href = $('.js-add-to-cart-buy-now').attr('href');
     let form = $('#js-form-add-cart');
      $.ajax({
    url: '/shop/cart/add-to-cart',
    method: 'POST',
    data: form.serialize(),
    success(res) {
      if (href && href !== '#') {
        window.location = href;
      }
    },
  });
}
$(document).on('click','.js-buy-now-confirm',function(e) {
  e.preventDefault()
buyNow()
})

JS;
$this->registerJs($js) ?>
