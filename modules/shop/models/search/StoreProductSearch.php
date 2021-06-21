<?php

namespace app\modules\shop\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\StoreProduct;

/**
 * StoreProductSearch represents the model behind the search form of `app\modules\shop\models\StoreProduct`.
 */
class StoreProductSearch extends StoreProduct
{
	
	public $search;
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'store_id', 'product_id'], 'integer'],
			[['sku', 'product_name', 'shipping', 'note', 'connected', 'search'], 'safe'],
			[['price', 'old_price'], 'number'],
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
		$query = StoreProduct::find()
		                     ->where(['store_id' => \Yii::$app->user->identity->store->id])
		                     ->orderBy(['store_product.product_id' => SORT_ASC]);
		
		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
			'pagination' => [
				'pageSize' => \Yii::$app->session->get('page-size'),
			],
		]);
		
		$this->load($params);
		
		if($this->product_name) {
			$this->product_name = preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->product_name);
			$select = [];
			$condition = ['OR'];
			
			foreach(explode(' ', $this->product_name) as $search) {
				$select[] = "(product_name ilike '%$search%')::int";
				$select[] = "(sku ilike '%$search%')::int";
				$condition[] = ['ilike', 'product_name', $search];
				$condition[] = ['ilike', 'sku', $search];
			}
			
			$query->andFilterWhere($condition);
			$select = implode('+', $select);
			$select .= ' as relevance';
			$query->addSelect(['*', $select]);
			$query->orderBy(['relevance' => SORT_DESC]);
		}
		$query->andFilterWhere([
			'connected' => $this->connected,
		]);
		
		return $dataProvider;
	}
}
