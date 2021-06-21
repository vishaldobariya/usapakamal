<?php

namespace app\modules\shop\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\OrderItem;

/**
 * OrderItemSearch represents the model behind the search form of `app\modules\shop\models\OrderItem`.
 */
class OrderItemSearch extends OrderItem
{
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'order_id', 'qty'], 'integer'],
			[['product_price'], 'number'],
			[['product_id'], 'safe'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}
	
	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = OrderItem::find()->where(['order_id' => $params])->distinct()
		                                                          ->with(['product.images','engravings.imageFront']);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'            => $this->id,
			'order_id'      => $this->order_id,
			'product_price' => $this->product_price,
			'qty'           => $this->qty,
		]);
		
		$query->andFilterWhere(['ilike', 'product_id', $this->product_id]);
		
		return $dataProvider;
	}
	
	public function searchProvider($params)
	{
		$query = OrderItem::find()
		                  ->where(['order_id' => $params])
		                  ->with(['product.images', 'product.storeProduct','engravings']);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'            => $this->id,
			'order_id'      => $this->order_id,
			'product_price' => $this->product_price,
			'qty'           => $this->qty,
		]);
		
		$query->andFilterWhere(['ilike', 'product_id', $this->product_id]);
		
		return $dataProvider;
	}
}
