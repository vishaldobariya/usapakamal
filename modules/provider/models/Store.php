<?php

namespace app\modules\provider\models;

use app\modules\settings\models\State;
use app\modules\settings\models\StoreState;
use app\modules\user\models\User;
use app\modules\shop\models\Order;
use app\modules\shop\models\StoreProduct;

/**
 * This is the model class for table "store".
 *
 * @property int            $id
 * @property int|null       $user_id
 * @property string|null    $name
 * @property string|null    $api_key
 * @property string|null    $api_secret
 * @property bool|null      $connected
 *
 * @property StoreProduct[] $storeProducts
 */
class Store extends \yii\db\ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'store';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['user_id'], 'default', 'value' => null],
			[['user_id'], 'integer'],
			[['name'], 'string', 'max' => 255],
			[['api_secret', 'api_key', 'connected'], 'safe'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'      => 'ID',
			'user_id' => 'User ID',
			'name'    => 'Name',
		];
	}
	
	/**
	 * Gets query for [[StoreProducts]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getStoreProducts()
	{
		return $this->hasMany(StoreProduct::className(), ['store_id' => 'id']);
	}
	
	public function getUser()
	{
		return $this->hasOne(User::class, ['id' => 'user_id']);
	}
	
	public function getOrders()
	{
		return $this->hasMany(Order::class, ['store_id' => 'id']);
	}
	
	public function getState()
	{
		return $this->hasMany(State::class, ['id' => 'state_id'])->viaTable(StoreState::tableName(), ['store_id' => 'id']);
	}
	
}
