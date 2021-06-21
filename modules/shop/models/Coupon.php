<?php

namespace app\modules\shop\models;

use Yii;

/**
 * This is the model class for table "coupon".
 *
 * @property int          $id
 * @property string|null  $name
 * @property string|null  $description
 * @property float|null   $value
 * @property int|null     $status
 * @property bool|null    $is_percent
 * @property bool|null    $is_usd
 * @property int|null     $start_date
 * @property int|null     $end_date
 * @property bool|null    $is_products_with_ship
 * @property bool|null    $is_only_products
 * @property bool|null    $is_only_ship
 * @property bool|null    $safe_delete
 * @property float|null   $min_cart_price
 *
 * @property CouponUser[] $couponUsers
 */
class Coupon extends \yii\db\ActiveRecord
{
	
	const STATUSES = [
		0 => 'Active',
		1 => 'Inactive',
	];
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'coupon';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['description'], 'string'],
			[['value', 'min_cart_price'], 'number'],
			[['status', 'start_date', 'end_date'], 'default', 'value' => null],
			[['status'], 'integer'],
			[['is_percent', 'is_usd', 'is_products_with_ship', 'is_only_products', 'is_only_ship','safe_delete'], 'boolean'],
			[['name'], 'string', 'max' => 255],
			[['start_date', 'end_date'], 'required'],
			['name', 'unique', 'targetAttribute' => ['name']],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'                    => 'ID',
			'name'                  => 'Name',
			'description'           => 'Description',
			'value'                 => 'Value',
			'status'                => 'Status',
			'is_percent'            => 'Is Percent',
			'is_usd'                => 'Is Usd',
			'start_date'            => 'Start Date',
			'end_date'              => 'End Date',
			'is_products_with_ship' => 'Is Products With Ship',
			'is_only_products'      => 'Is Only Products',
			'is_only_ship'          => 'Is Only Ship',
			'min_cart_price'        => 'Min Cart Price',
		];
	}
	
	/**
	 * Gets query for [[CouponUsers]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCouponUsers()
	{
		return $this->hasMany(CouponUser::className(), ['coupon_id' => 'id']);
	}
	
	public function isValid()
	{
		$data = [];
		if($this->status == 1) {
			$data['status'] = 'Error';
			$data['message'] = 'Coupon is invalid';
			
		}
		
		if($this->start_date > time()) {
			$data['status'] = 'Error';
			$data['message'] = 'Coupon has not started';
			
		}
		
		if($this->end_date < time()) {
			$data['status'] = 'Error';
			$data['message'] = 'Coupon has finished';
			
		}
		
		if($this->min_cart_price > Yii::$app->cart->cost) {
			$data['status'] = 'Error';
			$data['message'] = 'The cart amount must be greater $' . $this->min_cart_price;
		}
		
		if(!empty($this->couponUsers)) {
			if(!Yii::$app->user->isGuest) {
				$cu = CouponUser::find()->where(['coupon_id' => $this->id, 'email' => Yii::$app->user->identity->email])->one();
				if(!$cu) {
					$data['status'] = 'Error';
					$data['message'] = 'Coupon is invalid';
				}
				
				if($cu && $cu->count == 0) {
					$data['status'] = 'Error';
					$data['message'] = 'Coupon is invalid';
				}
				
			}
			
			if(Yii::$app->user->isGuest && !Yii::$app->session->has('customer')) {
				$data['status'] = 'Error';
				$data['message'] = 'Coupon for authorized users only';
				
			}
			
			if(Yii::$app->session->has('customer')) {
				if(!CouponUser::find()->where(['coupon_id' => $this->id, 'email' => Yii::$app->session->get('customer')->email])->exists()) {
					$data['status'] = 'Error';
					$data['message'] = 'Coupon is invalid';
					
				}
			}
			
		}
		
		return $data;
	}
	
	public function getCouponPrice($total_prod, $ship = null)
	{
		$coup_price = 0;
		
		if($this->is_percent) {
			if($this->is_only_products) {
				$coup_price = $total_prod * ((float)$this->value) / 100;
			}
			
			if($this->is_products_with_ship) {
				$coup_price = (((float)(Yii::$app->session->get('shipping')?? $ship)) + $total_prod) * ((float)$this->value) / 100;
			}
			
			if($this->is_only_ship) {
				
				$coup_price = ((float)(Yii::$app->session->get('shipping') ?? $ship)) * ((float)$this->value) / 100;
			}
		}
		
		if($this->is_usd) {
			$coup_price = (float)$this->value;
		}
		
		return $coup_price;
	}
}
