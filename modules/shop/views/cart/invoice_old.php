<?php
/**
 * @author      Anthony <xristmas365@gmail.com>
 * @copyright   industrialax.com
 * @license     https://industrialax.com
 */

?>

<div class="row">
  <div class="col-xs-12">
    <div class="invoice-title">
      <h2>Invoice</h2>
      <h3 class="text-right"># <?= 10000 + $order->id ?></h3>
    </div>
    <hr>
    <table class="table table-bordered">
      <thead>
      <tr>
        <td><strong>Billed To:</strong></td>
        <td><strong>Shipped To:</strong></td>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>
          <address>
			  <?= $order->customer->fullname ?><br>
	          <?= $order->customer->address . ' ' . $order->customer->adress_two ?>
          </address>
        </td>
        <td>
          <address>
			 <?= $order->customer->fullname ?><br>
	          <?= $order->customer->address . ' ' . $order->customer->adress_two ?>
          </address>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><strong>Invoice summary</strong></h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-condensed">
            <thead>
            <tr>
              <td>Image</td>
              <td><strong>Item</strong></td>
              <td class="text-center"><strong>SKU</strong></td>
              <td class="text-center"><strong>Price</strong></td>
              <td class="text-center"><strong>Quantity</strong></td>
              <td class="text-right"><strong>Totals</strong></td>
            </tr>
            </thead>
            <tbody>
			
			<?php foreach(Yii::$app->cart->positions as $item) : ?>
				<tr>
					<td><img width="70px" height="100px" src="<?= $item->thumb ?>" alt=""></td>
                <td><?= $item->name ?></td>
                <td class="text-center"><?= $item->sku ?></td>
                <td class="text-center"><?= Yii::$app->formatter->asCurrency($item->getPrice()) ?></td>
                <td class="text-center"><?= $item->quantity ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asCurrency($item->getPrice() * $item->quantity) ?></td>
              </tr>
			<?php endforeach ?>
			<tr>
              <td class="thick-line"></td>
              <td class="thick-line"></td>
              <td class="thick-line"></td>
              <td class="thick-line"></td>
              <td class="thick-line text-center"><strong>Shipping</strong></td>
              <td class="thick-line text-right"><?= Yii::$app->formatter->asCurrency(Yii::$app->session->get('shipping')) ?></td>
            </tr>
			<tr>
              <td class="thick-line"></td>
              <td class="thick-line"></td>
              <td class="thick-line"></td>
              <td class="thick-line"></td>
              <td class="thick-line text-center"><strong>Total</strong></td>
              <td class="thick-line text-right"><?= Yii::$app->formatter->asCurrency(Yii::$app->cart->cost + Yii::$app->session->get('shipping')) ?></td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
