<?php

namespace app\modules\shop\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\Order;

/**
 * OrderSearch represents the model behind the search form of `app\modules\shop\models\Order`.
 */
class OrderSearch extends Order
{
	
	public $created_at_range;
	
	public $no;
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'created_at','no'], 'integer'],
			[['total_cost'], 'number'],
			[['transaction_id', 'customer_id', 'store_id', 'status', 'created_at_range'], 'safe'],
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
		$query = Order::find()
		              ->with('items')
		              ->joinWith(['store', 'customer.user', 'couponModel']);
		
		$query->andFilterWhere(['status' => $this->status]);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => ['defaultOrder' => ['id' => SORT_DESC]],
			'pagination' => [
				'pageSize' => \Yii::$app->session->get('page-size'),
			],
		]);
		
		$this->load($params);
		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		if($this->no) {
			$query->andFilterWhere([
				'order.id' => ((int)$this->no - 1000) < 0 ? null : ((int)$this->no - 1000),
			]);
		}
		if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
			list($start_date, $end_date) = explode(' - ', $this->created_at_range);
			$query->andFilterWhere(['between', 'order.created_at', strtotime($start_date), strtotime($end_date)]);
		}
		
		if($this->store_id == '0') {
			$query->andWhere(['is', 'order.store_id', null]);
		} else {
			$query->andFilterWhere([
				'order.store_id' => $this->store_id,
			]);
		}
		
		$query->andFilterWhere([
			'order.id'     => $this->id,
			'created_at'   => $this->created_at,
			'total_cost'   => $this->total_cost,
			'order.status' => $this->status,
		]);
		$query->andFilterWhere(['ilike', 'transaction_id', $this->transaction_id]);
		
		$query->andFilterWhere(['ilike', "CONCAT(customer.first_name,' ',customer.last_name)", $this->customer_id]);
		
		return $dataProvider;
	}
	
	public function searchProvider($params)
	{
		$query = Order::find()->where(['store_id' => \Yii::$app->user->identity->store->id])->with(['customer', 'items', 'couponModel']);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => ['defaultOrder' => ['id' => SORT_DESC]],
		]);
		
		$this->load($params);
		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		// grid filtering conditions
		$query->andFilterWhere([
			'id'           => $this->id,
			'customer_id'  => $this->customer_id,
			'created_at'   => $this->created_at,
			'total_cost'   => $this->total_cost,
			'order.status' => $this->status,
		]);
		
		$query->andFilterWhere(['ilike', 'transaction_id', $this->transaction_id]);
		
		return $dataProvider;
	}
	
	public function searchUser($params)
	{
		$query = Order::find()
		              ->joinWith(['store', 'customer', 'couponModel'])
		              ->where(['order.user_id' => \Yii::$app->user->id])
		              ->andWhere(['!=', 'order.status', 6])
		              ->orderBy(['id' => SORT_DESC]);
		
		$query->andFilterWhere(['status' => $this->status]);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
			'sort'       => ['defaultOrder' => ['id' => SORT_DESC]],
			'pagination' => [
				'pageSize' => \Yii::$app->session->get('page-size'),
			],
		]);
		
		$this->load($params);
		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		if($this->id) {
			$query->andFilterWhere([
				'order.id' => $this->id - 1000,
			]);
		}
		
		$query->andFilterWhere([
			'order.store_id' => $this->store_id,
			'created_at'     => $this->created_at,
			'total_cost'     => $this->total_cost,
			'order.status'   => $this->status,
		]);
		$query->andFilterWhere(['ilike', 'transaction_id', $this->transaction_id]);
		
		$query->andFilterWhere(['ilike', "CONCAT(customer.first_name,' ',customer.last_name)", $this->customer_id]);
		
		return $dataProvider;
	}
	
	public function searchUserSaved($params)
	{
		$query = Order::find()
		              ->joinWith(['store', 'customer', 'couponModel'])
		              ->where(['order.user_id' => \Yii::$app->user->id])
		              ->andWhere(['order.status' => 6]);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
			'sort'       => ['defaultOrder' => ['id' => SORT_DESC]],
			'pagination' => [
				'pageSize' => \Yii::$app->session->get('page-size'),
			],
		]);
		
		$this->load($params);
		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		if($this->id) {
			$query->andFilterWhere([
				'order.id' => $this->id - 1000,
			]);
		}
		
		$query->andFilterWhere([
			'order.store_id' => $this->store_id,
			'created_at'     => $this->created_at,
			'total_cost'     => $this->total_cost,
		]);
		$query->andFilterWhere(['ilike', 'transaction_id', $this->transaction_id]);
		
		$query->andFilterWhere(['ilike', "CONCAT(customer.first_name,' ',customer.last_name)", $this->customer_id]);
		
		return $dataProvider;
	}
	
	public function searchExport($params)
	{
		$query = Order::find()
		              ->with(['items.product','items.engravings'])
		              ->joinWith(['store', 'customer.user', 'couponModel'])->asArray();
		
		$query->andFilterWhere(['status' => $this->status]);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => ['defaultOrder' => ['id' => SORT_DESC]],
			'pagination' => false,
		
		]);
		
		$this->load($params);
		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		if($this->id) {
			$query->andFilterWhere([
				'order.id' => $this->id - 1000,
			]);
		}
		if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
			list($start_date, $end_date) = explode(' - ', $this->created_at_range);
			$query->andFilterWhere(['between', 'order.created_at', strtotime($start_date), strtotime($end_date)]);
		}
		
		if($this->store_id == '0') {
			$query->andWhere(['is', 'order.store_id', null]);
		} else {
			$query->andFilterWhere([
				'order.store_id' => $this->store_id,
			]);
		}
		if($this->no) {
			$query->andFilterWhere([
				'order.id' => ((int)$this->no - 1000) < 0 ? null : ((int)$this->no - 1000),
			]);
		}
		
		$query->andFilterWhere([
			'created_at'   => $this->created_at,
			'total_cost'   => $this->total_cost,
			'order.status' => $this->status,
		]);
		$query->andFilterWhere(['ilike', 'transaction_id', $this->transaction_id]);
		
		$query->andFilterWhere(['ilike', "CONCAT(customer.first_name,' ',customer.last_name)", $this->customer_id]);
		
		return $dataProvider;
	}
}
