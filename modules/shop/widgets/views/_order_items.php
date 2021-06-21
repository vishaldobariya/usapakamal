<?php
/**
 * @author      Anthony <xristmas365@gmail.com>
 * @copyright   industrialax.com
 * @license     https://industrialax.com/crm-general-license
 */

use app\components\AdminGrid;

?>
<?= AdminGrid::widget([
	'id'           => 'orders-grid',
	'summary'      => false,
	'export'       => false,
	'bordered'     => false,
	'bootstrap'    => false,
	'dataProvider' => $dataProvider,
	'columns'      => [
		[
			'label' => 'Product',
			'value' => function($model)
			{
				return $model->product->name;
			},
		
		],
		[
			'label' => 'Product(count)',
			'value' => function($model)
			{
				return $model->qty;
			},
		],
		[
			'label' => 'Price per item',
			'value' => function($model)
			{
				return Yii::$app->formatter->asCurrency($model->product_price);
			},
		],
		[
			'label' => 'Total',
			'value' => function($model)
			{
				return Yii::$app->formatter->asCurrency($model->product_price * $model->qty + ($model->engravings->front_price ?? 0));
			},
		],
		[
			'label' => 'Engraving',
			'value' => function($model)
			{
				$sum = 0;
				foreach($model->engravings as $eng) {
					$sum += $eng->front_price * $eng->qty;
				}
				
				return Yii::$app->formatter->asCurrency($sum);
			},
		],
		
	],
]); ?>
