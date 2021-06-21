<?php

namespace app\modules\shop\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\Product;

/**
 * ProductSearch represents the model behind the search form of `app\modules\shop\models\Product`.
 */
class ProductSearch extends Product
{
	
	public $image_sort;
	
	public $needle;
	
	public $brand_name;
	
	public $sale;
	
	public $search;
	
	public $year_from;
	
	public $year_to;
	
	public $order;
	
	public $created_at_range;
	
	public $name_image;
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'category_id', 'brand_id', 'store_id', 'vol'], 'integer'],
			[
				[
					'price',
					'name',
					'description',
					'slug',
					'needle',
					'sku',
					'brand_name',
					'cap',
					'sale',
					'available',
					'visible',
					'special_offers',
					'featured_brand',
					'search',
					'year_from',
					'year_to',
					'catalogs',
					'available',
					'image_sort',
					'order',
					'sub_category_id',
					'created_at_range',
					'name_image',
				
				],
				'safe',
			],
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
		                ->joinWith(['images'])
		                ->with(['brand.images', 'category.images', 'provider', 'storeProducts.store'])
		                ->orderBy(['id' => SORT_DESC]);
		
		// ->joinWith(['images']);
		
		//if($this->image_sort) {
		//	$sort = $this->image_sort == 'ASC' ? SORT_ASC : SORT_DESC;
		//	$query->orderBy(['count_image' => $sort]);
		//} else {
		//	$query->orderBy(['product.id' => SORT_DESC]);
		//}
		//
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
			'pagination' => [
				'pageSize' => \Yii::$app->session->get('page-size'),
			],
		]);
		
		$this->load($params);
		
		// grid filtering conditions
		$query->andFilterWhere([
			'product.id'      => $this->id,
			'category_id'     => $this->category_id,
			'brand_id'        => $this->brand_id,
			'cap'             => $this->cap,
			'featured_brand'  => $this->featured_brand,
			'special_offers'  => $this->special_offers,
			'vol'             => $this->vol,
			'available'       => $this->available,
			'visible'         => $this->visible,
			'sub_category_id' => $this->sub_category_id,
			'age'             => $this->age,
		
		]);
		
		if($this->search) {
			//$ids = [];
			$this->search = preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->search);
			
			$select = [];
			foreach(explode(' ', $this->search) as $search) {
				$select[] = "(product.name ilike '%$search%')::int";
				$query->orFilterWhere(['ilike', 'product.name', $search]);
				$query->orFilterWhere(['ilike', 'sku', $search]);
			}
			$select = implode('+', $select);
			$select .= ' as relevance';
			$query->addSelect(['product.*', $select]);
			$query->orderBy(['relevance' => SORT_DESC]);
			
		}
		
		if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
			list($start_date, $end_date) = explode(' - ', $this->created_at_range);
			$query->andFilterWhere(['between', 'year', date('Y', strtotime($start_date)), date('Y', strtotime($end_date))]);
		}
		
		if($this->price) {
			$prices = explode('-', $this->price);
			if(count($prices) > 1) {
				$query->andFilterWhere(['between', 'price', (float)$prices[0], (float)$prices[1]]);
			} else {
				$query->andFilterWhere(['price' => (float)$prices[0]]);
			}
		}
		
		$query->andFilterWhere(['ilike', 'product.name', $this->name])
		      ->andFilterWhere(['ilike', 'description', $this->description])
		      ->andFilterWhere(['ilike', 'sku', $this->sku])
		      ->andFilterWhere(['ilike', 'catalogs', $this->catalogs])
		      ->andFilterWhere(['ilike', 'storage.name', $this->name_image])
		      ->andFilterWhere(['ilike', 'slug', $this->slug]);
		
		return $dataProvider;
	}
	
	public function searchWithoutImage($params)
	{
		$query = Product::find()
			//->select(['*', 'COUNT(storage.id) as count_image'])
			            ->joinWith('images')
		                ->with(['brand.images', 'category.images', 'provider', 'storeProducts.store'])
		                ->where(['is', 'storage.id', null]);
		// ->joinWith(['images']);
		
		//if($this->image_sort) {
		//	$sort = $this->image_sort == 'ASC' ? SORT_ASC : SORT_DESC;
		//	$query->orderBy(['count_image' => $sort]);
		//} else {
		//	$query->orderBy(['product.id' => SORT_DESC]);
		//}
		//
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
			'pagination' => [
				'pageSize' => \Yii::$app->session->get('page-size'),
			],
		]);
		
		$this->load($params);
		
		// grid filtering conditions
		$query->andFilterWhere([
			'product.id'     => $this->id,
			'category_id'    => $this->category_id,
			'brand_id'       => $this->brand_id,
			'cap'            => $this->cap,
			'featured_brand' => $this->featured_brand,
			'special_offers' => $this->special_offers,
			'vol'            => $this->vol,
			'available'      => $this->available,
			'visible'        => $this->visible,
		
		]);
		
		if($this->search) {
			//$ids = [];
			$this->search = preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->search);
			
			$select = [];
			foreach(explode(' ', $this->search) as $search) {
				$select[] = "(product.name ilike '%$search%')::int";
				$query->orFilterWhere(['ilike', 'product.name', $search]);
				$query->orFilterWhere(['ilike', 'sku', $search]);
			}
			$select = implode('+', $select);
			$select .= ' as relevance';
			$query->addSelect(['*', $select]);
			$query->orderBy(['relevance' => SORT_DESC]);
			
		}
		
		if($this->price) {
			$prices = explode('-', $this->price);
			if(count($prices) > 1) {
				$query->andFilterWhere(['between', 'price', (float)$prices[0], (float)$prices[1]]);
			} else {
				$query->andFilterWhere(['price' => (float)$prices[0]]);
			}
		}
		
		$query->andFilterWhere(['ilike', 'product.name', $this->name])
		      ->andFilterWhere(['ilike', 'description', $this->description])
		      ->andFilterWhere(['ilike', 'sku', $this->sku])
		      ->andFilterWhere(['ilike', 'catalogs', $this->catalogs])
		      ->andFilterWhere(['ilike', 'slug', $this->slug]);
		
		return $dataProvider;
	}
	
	public function searchFront($params)
	{
		$query = Product::find()
		                ->with(['brand', 'images', 'category.images', 'brand.images', 'subCategory.images'])
		                ->distinct()
		                ->joinWith('category')
			//->where(['category.status' => 1])
			            ->where(['product.visible' => true])
		                ->groupBy(['product.id']);
		
		// add conditions that should always apply here
		
		$this->load($params);
		if($this->price != null && $this->price != 'All') {
			$condition = ['OR'];
			$prices = explode('-', $this->price);
			$condition[] = ['between', 'price', $prices[0] * 0.7, $prices[1] * 0.7];
			$query->andFilterWhere($condition);
		}
		
		if($this->needle) {
			$needle = preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->needle);
			$query->addSelect(['product.*', "word_similarity(product.name, '$needle') as rel"])
			      ->having(['>', "word_similarity(product.name, '$needle')", 0.2])
			      ->orderBy(['rel' => SORT_DESC]);
		}
		// grid filtering conditions
		if($this->order) {
			switch($this->order) {
				case 0:
					$query->orderBy(['id' => SORT_ASC]);
					break;
				case 1:
					$query->orderBy(['price' => SORT_ASC]);
					break;
				case 2:
					$query->orderBy(['price' => SORT_DESC]);
					break;
				case 3:
					$query->orderBy(['name' => SORT_ASC]);
					break;
				case 4:
					$query->orderBy(['name' => SORT_DESC]);
					break;
				default:
					$query->orderBy(['id' => SORT_ASC]);
			}
		}
		
		$query->andFilterWhere([
			'id'              => $this->id,
			'category_id'     => $this->category_id,
			'sub_category_id' => $this->sub_category_id,
			'brand_id'        => $this->brand_id,
			'featured_brand'  => $this->featured_brand,
			'special_offers'  => $this->special_offers,
			'store_id'        => $this->store_id,
		]);
		
		$query->andFilterWhere(['>', 'age', $this->year_from]);
		$query->andFilterWhere(['<', 'age', $this->year_to]);
		
		$query->andFilterWhere(['ilike', 'description', $this->description])
		      ->andFilterWhere(['ilike', 'slug', $this->slug])
		      ->andFilterWhere(['ilike', 'tags', $this->sale]);
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			//'pagination' => false,
		]);
		
		return $dataProvider;
	}
	
	public function searchAlert($params)
	{
		$query = Product::find()
		                ->with(['images', 'storeProducts'])
		                ->having(['<', '(price-provider_price)/provider_price', 0.3])
		                ->groupBy(['product.id']);
		
		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
			'pagination' => [
				'pageSize' => \Yii::$app->session->get('page-size'),
			],
		]);
		
		$this->load($params);
		
		if($this->price) {
			$prices = explode('-', $this->price);
			if(count($prices) > 1) {
				$query->andFilterWhere(['between', 'price', (float)$prices[0], (float)$prices[1]]);
			} else {
				$query->andFilterWhere(['price' => (float)$prices[0]]);
			}
		}
		
		$query->andFilterWhere(['ilike', 'product.name', $this->name])
		      ->andFilterWhere(['ilike', 'description', $this->description])
		      ->andFilterWhere(['ilike', 'sku', $this->sku])
		      ->andFilterWhere(['ilike', 'catalogs', $this->catalogs])
		      ->andFilterWhere(['ilike', 'slug', $this->slug]);
		
		return $dataProvider;
		
	}
	
}
