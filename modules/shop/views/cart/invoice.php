<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use app\modules\shop\models\Order;

/**
 * @var $order Order
 */

?>
<div class="pdf">
    <div class="pdf-logo text-center">
        <img src="images/logo-gold.png" width="90" alt="">
    </div>
    <div class="">
        <p class="header">Packing slip</p>
    </div>
    <div class="grid">
        <div class="col col-1">
            <hr>
            <h2>Shipping Address</h2>
            <div class="mb-10"><?= $order->customer->name ?></div>
            <div class="mb-10">
                <?= $order->customer->address . ' ' . $order->customer->adress_two ?>
            </div>
            <div class="mb-10">
                <?= $order->customer->contry ?> - <?= $order->customer->city ?>
            </div>
            <div class="mb-10">
                <?= $order->customer->zip ?>
            </div>
        </div>
        <div class="col col-2">
            <hr>
            <h2>Order Details</h2>
            <div class="detail-item mb-10">
                <div class="detail-title">Order Number</div>
                <div class="detail-data"><?= 10000 + $order->id ?></div>
            </div>
            <div class="detail-item mb-10">
                <div class="detail-title">Order Date</div>
                <div class="detail-data"><?= date("F j, Y", $order->created_at); ?>
                </div>
            </div>
            <div class="detail-item mb-10">
                <div class="detail-title">Shipment number</div>
                <div class="detail-data">text
                </div>
            </div>
        </div>
        <hr>
    </div>
    <div class="item">
        <h2>Items</h2>
	    <?php foreach(Yii::$app->cart->positions as $item) : ?>
		    <?php if($item->formName() == 'Product') : ?>
			    <table>
            <tbody>
                <tr>
                    <td style="font-size: 40px; font-weight: bold;    font-family: Arial, Helvetica, sans-serif;">
                       <?= $item->quantity ?>
                    </td>
                    <td style="font-size:26px; padding: 0 10px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">
                        x
                    </td>
                    <td style="font-size: 40px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">
                        <p style="font-size: 20px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">
                            <?= $item->name ?></p>
                        <p style="font-size: 12px; color: #999; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">
                            <?= $item->vol ?>ml, <?= Yii::$app->formatter->asDecimal($item->abv) . '%' ?>, SKU:<?= $item->sku ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
		    <?php endif; ?>
	    <?php endforeach; ?>

    </div>
</div>
