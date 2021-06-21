<?php

namespace app\modules\user\models;

use app\modules\shop\models\ShippingAddress;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\modules\admin\models\Theme;
use yii\behaviors\TimestampBehavior;
use app\modules\shop\models\Customer;
use app\modules\provider\models\Store;

/**
 * This is the model class for table "user".
 *
 * @property int         $id
 * @property int         $last_login_at
 * @property int         $created_at
 * @property string      $email
 * @property string|null $password
 * @property bool        $blocked
 * @property bool        $confirmed
 * @property string|null $auth_key
 * @property string      $role
 * @property string      $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property int|null    $zip
 * @property string|null $bio
 *
 * @property Theme       $theme
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
	
	public $new_pass;
	
	public $store_name;
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'user';
	}
	
	public static function findIdentity($id)
	{
		return static::find()->where(['id' => $id])->with(['theme'])->one();
	}
	
	public static function findIdentityByAccessToken($token, $type = null)
	{
		return static::findOne(['auth_key' => $token]);
	}
	
	public function validateAuthKey($authKey)
	{
		return $this->authKey === $authKey;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getAuthKey()
	{
		return $this->auth_key;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['email', 'first_name'], 'required'],
			[['blocked', 'confirmed'], 'boolean'],
			[['zip'], 'default', 'value' => null],
			[['zip', 'created_at', 'last_login_at', 'updated_at'], 'integer'],
			[['bio', 'new_pass', 'store_name'], 'safe'],
			[['email', 'password', 'auth_key', 'role', 'first_name', 'last_name', 'phone', 'address', 'city', 'state'], 'string', 'max' => 255],
			[['email'], 'unique'],
			['email', 'email'],
			[
				['store_name'],
				'required',
				'when'                   => function($model)
				{
					return $model->role == 'distributor';
					
				},
				'enableClientValidation' => false,
				'message'                => 'Store Name cannot be blank for role DISTRIBUTOR',
			],
		];
	}
	
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::class,
			],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'            => '#',
			'email'         => 'Email',
			'password'      => 'Password',
			'blocked'       => 'Blocked',
			'confirmed'     => 'Confirmed',
			'auth_key'      => 'Auth Key',
			'role'          => 'Role',
			'first_name'    => 'First Name',
			'last_name'     => 'Last Name',
			'phone'         => 'Phone',
			'address'       => 'Address',
			'city'          => 'City',
			'state'         => 'State',
			'zip'           => 'Zip',
			'bio'           => 'Bio',
			'created_at'    => 'Registered',
			'updated_at'    => 'Updated At',
			'last_login_at' => 'Last Login',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTheme()
	{
		return $this->hasOne(Theme::class, ['user_id' => 'id']);
	}
	
	public function getName()
	{
		return $this->first_name . ' ' . $this->last_name;
	}
	
	public function getStore()
	{
		return $this->hasOne(Store::class, ['user_id' => 'id']);
	}
	
	public function getCustomers()
	{
		return $this->hasMany(Customer::class, ['user_id' => 'id']);
	}
	
	public function getAddresses()
	{
		return $this->hasMany(ShippingAddress::class, ['user_id' => 'id']);
	}
	
	public function getDefaultAddress()
	{
		return $this->hasOne(ShippingAddress::class, ['user_id' => 'id'])->where(['is_default' => true]);
	}
}
