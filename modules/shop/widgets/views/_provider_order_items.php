<?php
/**
 * @author      Anthony <xristmas365@gmail.com>
 * @copyright   industrialax.com
 * @license     https://industrialax.com/crm-general-license
 */

use app\components\AdminGrid;
use kartik\grid\ActionColumn;
use app\modules\shop\models\OrderItem;

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
				/**
				 * @var $model OrderItem
				 */
				return Yii::$app->formatter->asCurrency($model->provider_price ?? 0);
			},
		],
		//[
		//	'label' => 'Royal Price',
		//	'value' => function($model)
		//	{
		//		/**
		//		 * @var $model OrderItem
		//		 */
		//		return Yii::$app->formatter->asCurrency($model->product_price);
		//	},
		//],
		[
			'label' => 'Total',
			'value' => function($model)
			{
				/**
				 * @var $model OrderItem
				 */
				return Yii::$app->formatter->asCurrency(($model->provider_price ?? 0) * $model->qty);
			},
		],
		[
			'class'    => ActionColumn::class,
			'header'   => false,
			'width'    => false,
			'template' => false,
			'buttons'  => false,
		],
	],
]); ?>
