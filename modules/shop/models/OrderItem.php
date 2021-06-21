<?php

namespace app\modules\shop\models;

/**
 * This is the model class for table "order_item".
 *
 * @property int        $id
 * @property int|null   $order_id
 * @property float|null $product_price
 * @property float|null $provider_price
 * @property int        $product_id
 * @property int|null   $qty
 */
class OrderItem extends \yii\db\ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'order_item';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['order_id', 'qty'], 'default', 'value' => null],
			[['order_id', 'qty'], 'integer'],
			[['product_price', 'provider_price'], 'number'],
			[['product_id'], 'integer'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'            => 'ID',
			'order_id'      => 'Order ID',
			'product_price' => 'Product Price',
			'product_id'    => 'Product ID',
			'qty'           => 'Qty',
		];
	}
	
	public function getOrder()
	{
		return $this->hasOne(Order::class, ['id' => 'order_id']);
	}
	
	public function getProduct()
	{
		return $this->hasOne(Product::class, ['id' => 'product_id']);
	}
	
	public function getEngravings()
	{
		return $this->hasMany(Engraving::class, ['order_item_id' => 'id']);
	}
}
