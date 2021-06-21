<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\models\search;

use app\modules\shop\models\Product;
use app\modules\shop\models\Store;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class StoreSearch extends Store
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['user_id', 'name'], 'integer'],
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
		$query = Product::find()
		                ->with(['brand.images', 'category.images', 'images'])
		                ->orderBy(['id' => SORT_ASC]);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => \Yii::$app->session->get('page-size'),
			],
		]);
		
		$this->load($params);
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'             => $this->id,
			'price'          => $this->price,
			'price_max'      => $this->price_max,
			'price_min'      => $this->price_min,
			'category_id'    => $this->category_id,
			'brand_id'       => $this->brand_id,
			'cap'            => $this->cap,
			'featured_brand' => $this->featured_brand,
			'special_offers' => $this->special_offers,
		
		]);
		
		$query->andFilterWhere(['ilike', 'name', $this->name])
		      ->andFilterWhere(['ilike', 'description', $this->description])
		      ->andFilterWhere(['ilike', 'sku', $this->sku])
		      ->andFilterWhere(['ilike', 'slug', $this->slug]);
		
		return $dataProvider;
	}
}
