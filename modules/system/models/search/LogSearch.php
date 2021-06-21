<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

namespace app\modules\system\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\system\models\Log;

/**
 * LogSearch represents the model behind the search form of `app\modules\system\models\Log`.
 */
class LogSearch extends Log
{
	
	public $search;
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'level'], 'integer'],
			[['category', 'prefix', 'message', 'search'], 'safe'],
			[['log_time'], 'number'],
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
		$query = Log::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => [
				'defaultOrder' => ['id' => SORT_DESC],
			],
		]);
		
		$this->load($params);
		
		if(!$this->validate()) {
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'       => $this->id,
			'level'    => $this->level,
			'log_time' => $this->log_time,
		]);
		
		$query
			->andFilterWhere(['ilike', 'category', $this->category])
			->andFilterWhere(['ilike', 'message', $this->search]);
		
		return $dataProvider;
	}
}
