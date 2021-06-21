<?php

namespace app\modules\shop\models;

use app\modules\settings\models\Zip;

/**
 * This is the model class for table "shipping_adress".
 *
 * @property int         $id
 * @property string|null $address
 * @property string|null $address_two
 * @property string|null $city
 * @property string|null $state
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $zip
 * @property bool|null   $is_default
 * @property int|null    $user_id
 */
class ShippingAddress extends \yii\db\ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'shipping_adress';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['is_default'], 'boolean'],
			[['user_id'], 'default', 'value' => null],
			[['user_id'], 'integer'],
			[['address', 'address_two', 'city', 'state', 'zip', 'first_name', 'last_name'], 'string', 'max' => 255],
			[['address', 'city', 'state', 'zip'], 'required'],
			[['zip'], 'forbidden'],
		];
	}
	
	public function forbidden($attribute, $params)
	{
		$zip = Zip::find()->where(['zipcode' => $this->$attribute])->one();
		
		if(!$zip || !$zip->active) {
			$this->addError($attribute, "Sorry! We apologize as we can't ship to your zip code. Please try to use a different address");
		}
		if(!Zip::find()->where(['zipcode' => $this->$attribute, 'state' => $this->state])->exists()) {
			$this->addError($attribute, "Enter a valid ZIP/postal code for " . $this->state . ", United States");
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'address'     => 'Address',
			'address_two' => 'Address Two',
			'city'        => 'City',
			'state'       => 'State',
			'zip'         => 'Zip',
			'is_default'  => 'Is Default',
			'user_id'     => 'User ID',
		];
	}
	
	public function getFull()
	{
		return $this->address . ',' . $this->address_two . ',<br>' . $this->city . ',' . $this->state . ' ' . $this->zip;
		
	}
}
