<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use kartik\form\ActiveForm;

if(isset(Yii::$app->request->get('ProductSearch')['needle'])){

}
?>
<main class="page collections-page">


    <ul class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Catalogue</li>

    </ul>


    <?php
    $get = Yii::$app->request->get('ProductSearch');
    ?>
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin([
                    'method'                 => 'get',
                    //'action'                 => Yii::$app->request->url,
                    'id'                     => 'js-form-filter',
                    'enableClientValidation' => false,
                    'validateOnBlur'         => false,
                    'validateOnType'         => true,
                ]) ?>
                <aside class="sidebar ">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="widget">
                            <h4><span>Price</span></h4>
                            <div class="widget-list form-group">
                                <select class="form-control" name="ProductSearch[price]" id="exampleFormControlSelect1">
                                    <option>All</option>
                                    <option value="0-100" <?= isset($get['price']) && $get['price'] == '0-100' ? 'selected' : '' ?>>$0 - $100</option>
                                    <option value="100-200" <?= isset($get['price']) && $get['price'] == '100-200' ? 'selected' : '' ?>>$100.00 - $200.00</option>
                                    <option value="200-300" <?= isset($get['price']) && $get['price'] == '200-300' ? 'selected' : '' ?>>$200.00 - $300.00</option>
                                    <option value="300-300000" <?= isset($get['price']) && $get['price'] == '300-300000' ? 'selected' : '' ?>>$300.00+</option>
                                </select>
                            </div>
                        </div>
                        <div class="widget">
                            <h4><span>Category</span></h4>

                            <?= $form->field($searchModel, 'category_id')->dropDownList($categories, ['prompt' => 'All'])->label(false) ?>
                        </div>
	                     <div class="widget">
                            <h4><span>Sub Category</span></h4>
		                     <?= $form->field($searchModel, 'sub_category_id')->dropDownList($subCats, ['prompt' => 'All'])->label
		                     (false) ?>
                        </div>
                        <div class="widget">
                            <h4><span>BRANDS</span></h4>
                            <?= $form->field($searchModel, 'brand_id')->dropDownList($brands, ['prompt' => 'All'])->label(false) ?>
                        </div>
                        <div class="widget">
                            <h4><span>Sort By</span></h4>
                            <?= $form->field($searchModel, 'order')->dropDownList([0 => 'Default Order', 1 => 'Lowest First', 2 => 'Highest First', 3 => 'A-Z', 4 => 'Z-A'])->label(false) ?>
                        </div>

                    </div>
                    <!-- <div class="widget">
                        <h3 class="widget-title"><span>Years</span></h3>
                        <ul class="widget-list">


                            <li>
                                <div class="custom-checkbox-wrap d-flex justify-content-around">
                                    <input class="w-25" value="<?= $get['year_from'] ?? '' ?>"
	                                    placeholder="From"
	                                       name="ProductSearch[year_from]"
	                                       type="number"
	                                       id="year_from">

	                                <input class="w-25" value="<?= $get['year_to'] ?? '' ?>"
	                                       placeholder="To"
		                                   name="ProductSearch[year_to]"
		                                   type="number"
		                                   id="year_to">
                                </div>
                            </li>


                        </ul>
                    </div> -->
                </aside>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-md-12 mb-4">
                <div class="collection ">
                    <div class="collection-inner padding-bottom">
                        <div class=" ">

                            <div class="grid row">
                                <?=
                                ListView::widget([
                                    'dataProvider' => $dataProvider,
                                    'itemOptions'  => ['tag' => null],
                                    'summary'      => false,
                                    //'pager'        => false,
                                    'sorter'       => [],
                                    'options'      => [
                                        'tag'   => 'div',
                                        'class' => 'd-flex flex-wrap w-100',
                                    ],
                                    //'layout'       => "\n{items}\n{pager}",
                                    'itemView'     => '_product',
                                ]);
                                ?>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<?php $js = <<< JS
$(document).on('change','select',function() {
  $('#js-form-filter').submit()
})
JS;
$this->registerJs($js); ?>
