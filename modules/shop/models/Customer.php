<?php

namespace app\modules\shop\models;

use app\modules\settings\models\Zip;
use app\modules\user\models\User;

/**
 * This is the model class for table "customer".
 *
 * @property int         $id
 * @property int|null    $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $address
 * @property string|null $adress_two
 * @property string|null $city
 * @property string|null $contry
 * @property string|null $state
 * @property string|null $zip
 * @property string|null $phone
 * @property string|null $billing_address
 * @property string|null $billing_address_two
 * @property string|null $billing_city
 * @property string|null $billing_country
 * @property string|null $billing_state
 * @property string|null $billing_zip
 * @property string|null $billing_phone'
 * @property string|null $billing_first_name
 * @property string|null $billing_last_name
 */
class Customer extends \yii\db\ActiveRecord
{
	
	public $test;
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'customer';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['first_name', 'last_name', 'email', 'address', 'adress_two', 'city', 'contry', 'zip', 'state'], 'string', 'max' => 255],
			['email', 'email'],
			[['first_name', 'last_name', 'email', 'address', 'city', 'contry', 'zip', 'phone'], 'required'],
			[
				[
					'billing_address',
					'billing_address_two',
					'billing_city',
					'billing_country',
					'billing_state',
					'billing_zip',
					'billing_phone',
					'billing_first_name',
					'billing_last_name',
				],
				'string',
				'max' => 255,
			],
			[['zip'], 'forbidden'],
			['user_id', 'safe'],
		];
	}
	
	public function forbidden($attribute, $params)
	{
		$zip = Zip::find()->where(['zipcode' => $this->$attribute])->one();
		
		if(!$zip || !$zip->active) {
			$this->addError($attribute, "Sorry! We apologize as we can't ship to your zip code. Please try to use a different address");
		}
		if(!Zip::find()->where(['zipcode' => $this->$attribute, 'state' => $this->state])->exists()){
			$this->addError($attribute, "Enter a valid ZIP/postal code for ".$this->state.", United States");
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'first_name' => 'First Name',
			'last_name'  => 'Last Name',
			'email'      => 'Email',
			'address'    => 'Address',
			'adress_two' => 'Adress Two',
			'city'       => 'City',
			'contry'     => 'Contry',
			'zip'        => 'Zip',
		];
	}
	
	public function getName()
	{
		return $this->first_name . ' ' . $this->last_name;
	}
	
	public function getBillingname()
	{
		return $this->billing_first_name . ' ' . $this->billing_last_name;
	}
	
	public function getFullAddress()
	{
		return $this->address . ', ' . ($this->adress_two ?? '') . ', ' . $this->city . ', ' . $this->state . ', ' . $this->zip;
	}
	
	public function getFullBillingAddress()
	{
		return $this->billing_address . ' ' . ($this->billing_address_two ?? '') . ', ' . $this->billing_city . ', ' . $this->billing_state . ', ' . $this->billing_zip;
		
	}
	
	public function getUser()
	{
		return $this->hasOne(User::class, ['id' => 'user_id']);
	}
}
