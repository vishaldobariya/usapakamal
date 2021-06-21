<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use yii\widgets\ActiveForm;
use app\modules\provider\models\Store;
use app\modules\shipstation\models\ShipStation;

/**
 * @var $store Store
 * @var $model ShipStation
 */
?>
<div class="product-form">

	<?php $form = ActiveForm::begin(); ?>
	<h3 class="text-center">Enter your Api Keys</h3>
	<?= $form->field($store, 'api_key')->textInput()->label('Shipstation Api Key') ?>
	<?= $form->field($store, 'api_secret')->textInput()->label('Shipstation Api Secret Key') ?>
	<input type="hidden" name="keys" value="1">
	
		<button type="submit" class="btn btn-primary mt-3"> Send</button>
	<?php ActiveForm::end(); ?>
	<h3 class="text-center mt-4">Or If you don't have them <a style="color: #b59049" target="_blank" href="https://www.shipstation.com/step1/">Create a Shipstation Account</a></h3>
</div>
