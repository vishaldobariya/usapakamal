<?php

namespace app\modules\shop\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "price".
 *
 * @property int        $id
 * @property int|null   $product_id
 * @property float|null $price
 * @property int|null   $created_at
 */
class Price extends \yii\db\ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'price';
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
	
	public static function addPrice($price, $product_id)
	{
		$model = new self;
		$model->price = $price;
		$model->product_id = $product_id;
		return $model->save();
	}
}
