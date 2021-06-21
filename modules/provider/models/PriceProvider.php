<?php

namespace app\modules\provider\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "price_provider".
 *
 * @property int        $id
 * @property int|null   $product_id
 * @property float|null $price
 * @property int|null   $created_at
 */
class PriceProvider extends \yii\db\ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'price_provider';
	}
	
	public function behaviors()
	{
		return [
			[
				'class'              => TimestampBehavior::class,
				'updatedAtAttribute' => false,
			],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['product_id', 'created_at'], 'default', 'value' => null],
			[['product_id', 'created_at'], 'integer'],
			[['price'], 'number'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'product_id' => 'Product ID',
			'price'      => 'Price',
			'created_at' => 'Created At',
		];
	}
}
