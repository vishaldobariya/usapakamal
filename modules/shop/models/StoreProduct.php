<?php

namespace app\modules\shop\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use app\modules\provider\models\Store;

/**
 * This is the model class for table "store_product".
 *
 * @property int         $id
 * @property int         $updated_at
 * @property int|null    $store_id
 * @property int|null    $product_id
 * @property int|null    $vol
 * @property int|null    $cap
 * @property bool        $connected
 * @property string|null $sku
 * @property string|null $product_name
 * @property float|null  $price
 * @property float|null  $old_price
 * @property float|null  $abv
 * @property string|null $shipping
 * @property string|null $note
 *
 * @property Store       $store
 */
class StoreProduct extends \yii\db\ActiveRecord
{
	
	public $relevance;
	
	public $total;
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'store_product';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['store_id'], 'default', 'value' => null],
			[['store_id', 'vol', 'cap', 'product_id'], 'integer'],
			[['price', 'old_price', 'abv'], 'number'],
			[['note'], 'string'],
			[['sku', 'product_name', 'shipping'], 'string', 'max' => 255],
			[['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
			[['connected', 'updated_at'], 'safe'],
		];
	}
	
	public function behaviors()
	{
		return [
			[
				'class'              => TimestampBehavior::class,
				'createdAtAttribute' => false,
			],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'           => 'ID',
			'store_id'     => 'Store ID',
			'sku'          => 'Sku',
			'product_name' => 'Product Name',
			'price'        => 'Price',
			'shipping'     => 'Shipping',
			'note'         => 'Note',
		];
	}
	
	/**
	 * Gets query for [[Store]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getStore()
	{
		return $this->hasOne(Store::class, ['id' => 'store_id']);
	}
	
	public static function selectExecutor($order = null)
	{
		$ids = [];
		$state = '';
		if($order == null) {
			$ids = ArrayHelper::getColumn(Yii::$app->cart->positions, 'id');
			$customer = Yii::$app->session->get('customer');
			$state = $customer->state;
		} else {
			$ids = ArrayHelper::getColumn($order->items, 'product_id');
			$state = $order->customer->state;
		}
		
		$store = self::find()
		             ->select(['*', 'SUM(price) as total'])
		             ->joinWith('store.state')
		             ->where(['product_id' => $ids])
		             ->andWhere(['state.short' => $state])
		             ->andWhere(['store_product.connected' => true])
		             ->groupBy(['store.id', 'store_product.id', 'store_state.id', 'state.id'])
		             ->orderBy(['total' => SORT_ASC])
		             ->all();
		
		if($order) {
			$stores_id = ArrayHelper::getColumn($store, 'store_id');
			$key = array_search(Yii::$app->user->identity->store->id, $stores_id);
			$store = array_slice($store, $key + 1);
		}
		
		if(!empty($store)) {
			return $store[0]->store_id;
			
		} else {
			return null;
		}
	}
}
