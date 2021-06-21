<?php

namespace app\modules\subscribe\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\subscribe\models\Subscribe;

/**
 * SubscribeSearch represents the model behind the search form of `app\modules\subscribe\models\Subscribe`.
 */
class SubscribeSearch extends Subscribe
{
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['email'], 'safe'],
			[['active'], 'boolean'],
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
		$query = Subscribe::find()->orderBy(['id' => SORT_DESC]);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		
		$this->load($params);
		
		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'     => $this->id,
			'active' => $this->active,
		]);
		
		$query->andFilterWhere(['ilike', 'email', $this->email]);
		
		return $dataProvider;
	}
}
