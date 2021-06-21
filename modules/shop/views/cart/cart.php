<?php

use kartik\form\ActiveForm;
use trntv\filekit\widget\Upload;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<main class="page">

  <div class="container-sm py-5 ">

    <h1 class="product-title p-0">Cart</h1>
    <div class="  mb-5 row mt-3">
      <div class="col-md-12">
        <div id="cart-products">
          <?php Pjax::begin(['id' => 'js-container-cart']) ?>
          <div class="card border-default cart-card">

            <div class="table-responsive ">


              <table class=" table   table-checkout mb-0">
                <thead>

                  <tr>
                    <th></th>
                    <th style="min-width: 130px; width: 130px"></th>
                    <th style="width: 40%"></th>

                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Summary</th>
                    <th></th>
                  </tr>

                </thead>
                <tbody>
                  <?php $total = 0 ?>
                  <?php foreach (Yii::$app->cart->positions as $key => $model) : ?>
                    <?php if ($model->formName() == 'Product') : ?>
                      <tr>
                        <td> </td>
                        <td><img src="<?= $model->thumb ?>" alt=""></td>
                        <td><a href="<?= Url::toRoute(['/shop/shop/product', 'slug' => $model->slug]) ?>" class="h5 text-dark">
                            <?= $model->name ?>

                          </a> <br>
                        </td>

                        <td><?= Yii::$app->formatter->asCurrency($model->getPrice()) ?></td>
                        <td><input type="number" data-price="<?= $model->getPrice() ?>" class="form-control form-control-sm js-cart-item-update" data-id="<?= $key ?>" min="1" step="1" name="cart-quantity" value="<?= $model->quantity ?>" style="width:60px;text-align:center"> </td>
                        <td class="js-cost-item"><?= Yii::$app->formatter->asCurrency($model->getPrice() * $model->quantity) ?></td>
                        <td>
                          <button type="button" data-id="<?= $key ?>" class="btn  btn-delete js-cart-item-delete">
                            <!-- <img src="/images/icon-delete.svg" width="40px" alt=""> -->
                            <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="trash-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-trash-alt fa-w-14 fa-3x">
                              <path fill="currentColor" d="M296 432h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8zm-160 0h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8zM440 64H336l-33.6-44.8A48 48 0 0 0 264 0h-80a48 48 0 0 0-38.4 19.2L112 64H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h24v368a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V96h24a8 8 0 0 0 8-8V72a8 8 0 0 0-8-8zM171.2 38.4A16.1 16.1 0 0 1 184 32h80a16.1 16.1 0 0 1 12.8 6.4L296 64H152zM384 464a16 16 0 0 1-16 16H80a16 16 0 0 1-16-16V96h320zm-168-32h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8z" class=""></path>
                            </svg>
                          </button>
                        </td>
                      </tr>
                      <?php $total += ($model->getPrice() * $model->quantity) ?>
                    <?php endif; ?>
                    <?php if ($model->formName() == 'Engraving') : ?>
                      <tr>
                        <td></td>
                        <td><img src="<?= !empty($model->data[0]['front_image']) ? $model->data[0]['front_image']['base_url'] . $model->data[0]['front_image']['path'] : '/images/icon-custom.svg'
                                      ?>" alt=""></td>
                        <td><a href="#" class="h5 js-edit-engraving" data-id="<?= $key ?>">Custom Engraving</a>
                          <h6 class="text-primary mb-0 mt-2"><strong>Engraving Message</strong></h6>
                          <p class="mb-0 mt-2"><small><?= !empty($model->data[0]['front_line_1']) ? $model->data[0]['front_line_1']  : '' ?></small></p>
                          <p class="mb-0"><small><?= !empty($model->data[0]['front_line_2']) ? $model->data[0]['front_line_2']  : '' ?></small></p>
                          <p class="mb-0"><small><?= !empty($model->data[0]['front_line_3']) ? $model->data[0]['front_line_3']  : '' ?></small></p>

                        </td>

                        <td><?= Yii::$app->formatter->asCurrency(\Yii::$app->settings->front_engraving) ?></td>
                        <td><input type="number" readonly class="form-control form-control-sm" min="1" step="1" name="cart-quantity" value="<?= $model->quantity ?>" style="width:60px;text-align:center"> </td>
                        <td class="js-cost-item"><?= Yii::$app->formatter->asCurrency(\Yii::$app->settings->front_engraving * $model->quantity) ?></td>
                        <td>
                          <button type="button" data-id="<?= $key ?>" class="btn   btn-delete js-engraving-delete">
                            <!-- <img src="/images/icon-delete.svg" width="40px" alt="">
                           -->
                            <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="trash-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-trash-alt fa-w-14 fa-3x">
                              <path fill="currentColor" d="M296 432h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8zm-160 0h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8zM440 64H336l-33.6-44.8A48 48 0 0 0 264 0h-80a48 48 0 0 0-38.4 19.2L112 64H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h24v368a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V96h24a8 8 0 0 0 8-8V72a8 8 0 0 0-8-8zM171.2 38.4A16.1 16.1 0 0 1 184 32h80a16.1 16.1 0 0 1 12.8 6.4L296 64H152zM384 464a16 16 0 0 1-16 16H80a16 16 0 0 1-16-16V96h320zm-168-32h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8z" class=""></path>
                            </svg>
                          </button>
                        </td>
                      </tr>
                      <?php $total += (float)Yii::$app->settings->front_engraving * $model->quantity ?>
                    <?php endif; ?>
                  <?php endforeach; ?>


                  <tr class="total-row  ">
                    <td style="width:50px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="js-cart-cost"><?= Yii::$app->formatter->asCurrency($total) ?></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>

            </div>

          </div>
          <div class="d-flex px-4 mt-4 justify-content-between d-md-none">
            <span class="h5 text-primary col-6">Total:</span>
            <span class="h5 text-primary col-6 text-right js-cart-cost"> <?= Yii::$app->formatter->asCurrency($total) ?> </span>
          </div>
          <?php Pjax::end() ?>
          <div class="my-4 d-flex justify-content-sm-end flex-wrap justify-content-center">
            <a href="<?= Yii::$app->request->referrer ?>" class="btn btn-primary btn-sm btn-cart ml-0  mr-3 mb-3">Go Back</a>
            <a href="<?= Url::toRoute(['/shop/shop/collections']) ?>" class="btn btn-primary btn-sm btn-cart ml-0  mr-3 mb-3">SHOP MORE</a>
            <a href="<?= Url::toRoute(['/shop/cart/information']) ?>" class="btn btn-secondary btn-sm btn-cart  mx-0 mb-3">Checkout</a>
          </div>
        </div>
      </div>

    </div>



  </div>
</main>

<!-- Modal Engrave Edit-->
<div class="modal fade" id="modalEngraving" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <?php ActiveForm::begin(['id' => 'js-engraving-form']) ?>
      <input type="hidden" class="form-control mb-3" value="" id="pos_id" name="pos_id">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Custom Engraving</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Option 1</strong></p>
            <div class=" ">
              <input type="text" class="form-control mb-3" id="val-1" name="front[front_line_1]" placeholder="Line 1">
              <input type="text" class="form-control mb-3" id="val-2" name="front[front_line_2]" placeholder="Line 2">
              <input type="text" class="form-control mb-3" id="val-3" name="front[front_line_3]" placeholder="Line 3">
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

            ]);

            ?>
            <p>Upload your logo file (.png). Please note that engraving logo should be in two colors only (Black and white).</p>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      <?php ActiveForm::end() ?>
    </div>
  </div>
</div>


<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php

$js = <<< JS


$(document).on('change','.js-cart-item-update',function() {
  let val = $(this).val()
  let id = $(this).data('id')
  let price = $(this).data('price')
  let item_price = Number(val) * Number(price)
  $(this).parent().next().text('$'+item_price.toFixed(2))
  $.post({
  url:'/shop/cart/cart-update',
  data:{id:id,val:val},
  success:function(res) {
    $('.js-count-cart').text(res.count);
    $('.js-cart-cost').text(res.cost)
    $('.js-cost-cart').text(res.cost)

  }
  })
})

$(document).on('click','.js-cart-item-delete',function() {
  let id = $(this).data('id')
  $.post({
    url:'/shop/cart/cart-item-delete',
    data:{id:id},
    success:function(res) {
      $('.js-count-cart').text(res.count);
      $('.js-cost-cart').text(res.cost)
      $.pjax.reload({container: "#js-container-cart", timeout: false})

    }
  })
})

$(document).on('click','.js-engraving-delete',function() {
  let id = $(this).data('id')
  $.post({
  url:'/shop/cart/delete-engraving',
  data:{id:id},
   success:function(res) {
      $.pjax.reload({container: "#js-container-cart", timeout: false})
     $('.js-count-cart').text(res.count);
    $('.js-cost-cart').text(res.cost)

    }
  })
})

$(document).on('click','.js-edit-engraving',function(e) {
  e.preventDefault()
  let id = $(this).data('id')
  $.post({
  url:'/shop/cart/get-data-engraving-by-id',
  data:{id:id},
  success:function(res) {
    $('#pos_id').val(id)
    $('#val-1').val(res.front_line_1)
    $('#val-2').val(res.front_line_2)
    $('#val-3').val(res.front_line_3)
    if(res.front_image !==''){
      let image = res.front_image

      $('.files.ui-sortable').html(`
      <li class="upload-kit-item done image" value="0">
      <img src="`+image.base_url+image.path+`">
      <input name="front[front_image][path]" value="`+image.path+`" type="hidden">
      <input name="front[front_image][name]" value="`+image.name+`" type="hidden">
      <input name="front[front_image][size]" value="`+image.size+`" type="hidden">
      <input name="front[front_image][type]" value="`+image.type+`" type="hidden">
      <input name="front[front_image][order]" type="hidden" data-role="order">
      <input name="front[front_image][base_url]" value="`+image.base_url+`" type="hidden">
      <span class="name" title="1.jpg"></span>
      <span class="fas fa-times-circle remove" data-url="/storage/default/delete?path=`+image.path+`"></span>
      </li>
      `)
      $('.upload-kit-input').css('display','none')
    }
    $('#modalEngraving').modal('show')
  }
  })

})

$(document).on('beforeSubmit','#js-engraving-form',function() {
  let form = $(this).serialize()
  $.post({
  url:'/shop/cart/update-engraving',
  data:form,
  success:function() {
    $.pjax.reload({container: "#js-container-cart", timeout: false})
    $('#modalEngraving').modal('hide')
     swal({
  title: "Ok!",
  text: "Custom Engraving was updated",
  icon: "success",
  button: true,
});
  }
  })
  return false;
})
JS;
$this->registerJs($js);
?>