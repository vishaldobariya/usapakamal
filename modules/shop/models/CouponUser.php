<?php

namespace app\modules\shop\models;

/**
 * This is the model class for table "coupon_user".
 *
 * @property int         $id
 * @property string|null $email
 * @property int|null    $count
 * @property int|null    $coupon_id
 *
 * @property Coupon      $coupon
 */
class CouponUser extends \yii\db\ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'coupon_user';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['count', 'coupon_id'], 'default', 'value' => null],
			[['count', 'coupon_id'], 'integer'],
			[['email'], 'safe'],
			[['coupon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coupon::className(), 'targetAttribute' => ['coupon_id' => 'id']],
			[['count', 'email'], 'required'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'        => 'ID',
			'email'     => 'Email',
			'count'     => 'Count',
			'coupon_id' => 'Coupon ID',
		];
	}
	
	/**
	 * Gets query for [[Coupon]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCoupon()
	{
		return $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
	}
}
