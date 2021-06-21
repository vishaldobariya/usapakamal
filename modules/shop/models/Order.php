<?php

namespace app\modules\shop\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\modules\provider\models\Store;

/**
 * This is the model class for table "order".
 *
 * @property int         $id
 * @property int|null    $customer_id
 * @property int|null    $created_at
 * @property int|null    $user_id
 * @property float|null  $total_cost
 * @property float|null  $discount_percent
 * @property float|null  $ship_price
 * @property float|null  $total_provider_cost
 * @property float|null  $coupon_percent
 * @property float|null  $tax
 * @property float|null  $tax_percent
 * @property string|null $transaction_id
 * @property string|null $coupon
 * @property string|null $ship_name
 * @property string|null $ship_code
 * @property string|null $note
 * @property string|null $note_name
 * @property string|null $note_email
 * @property string|null $discount_code
 * @property string|null $ship_order_key
 * @property string|null $ship_service_code
 * @property string|null $ship_id
 * @property string|null $ship_date
 * @property string|null $tracking_number
 * @property int|null    $status
 * @property int|null    $store_id
 * @property int|null    $provider_status
 */
class Order extends \yii\db\ActiveRecord
{
	
	public $sum;
	
	public $count_item;
	
	const STATUSES = [
		0 => 'New Paid',
		1 => 'Accepted',
		2 => 'Shipped',
		3 => 'Delivered',
		4 => 'Problematic',
		5 => 'Refused',
		6 => 'Not Paid',
		7 => 'Done',
	];
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'order';
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
			[['customer_id', 'created_at', 'status'], 'default', 'value' => null],
			[['customer_id', 'created_at', 'status', 'store_id'], 'integer'],
			[['total_cost', 'discount_percent', 'ship_price', 'total_provider_cost', 'coupon_percent'], 'number'],
			[
				[
					'transaction_id',
					'note',
					'discount_code',
					'ship_name',
					'ship_code',
					'ship_service_code',
					'ship_order_key',
					'ship_id',
					'ship_date',
					'tracking_number',
					'coupon',
					'note_name',
					'note_email',
				],
				'string',
			],
			[['user_id', 'tax', 'tax_percent'], 'safe'],
			['provider_status', 'default', 'value' => 0],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'             => 'ID',
			'customer_id'    => 'Customer ID',
			'created_at'     => 'Created At',
			'total_cost'     => 'Total Cost',
			'transaction_id' => 'Transaction ID',
			'status'         => 'Status',
		];
	}
	
	public function getItems()
	{
		return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
	}
	
	public function getCustomer()
	{
		return $this->hasOne(Customer::class, ['id' => 'customer_id']);
	}
	
	public function getStore()
	{
		return $this->hasOne(Store::class, ['id' => 'store_id']);
	}
	
	public function hasEngraving()
	{
		$i = 0;
		foreach($this->items as $item) {
			if($item->engravings) {
				$i++;
			}
		}
		
		return $i;
	}
	
	public function getCouponModel()
	{
		return $this->hasOne(Coupon::class, ['name' => 'coupon']);
	}
	
	public static function checkProviderPrice($product_id,$price)
	{
		$orders = self::find()->where(['store_id' => Yii::$app->user->identity->store->id,'order_item.product_id' => $product_id])->joinWith('items')->all();
		foreach($orders as $order){
			$total_provider = 0;
			foreach($order->items as $item){
				/**
				 * @var $item OrderItem
				 */
				
				if($item->product_id == $product_id && !$item->provider_price){
					$item->provider_price = $price;
					$item->save();
				}
				$total_provider += $item->qty * $item->provider_price;
				$order->total_provider_cost = $total_provider;
				$order->save();
			}
		}
	}
}
