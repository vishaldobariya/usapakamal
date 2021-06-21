<?php

namespace app\modules\shop\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\Coupon;

/**
 * CouponSearch represents the model behind the search form of `app\modules\shop\models\Coupon`.
 */
class CouponSearch extends Coupon
{
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'status'], 'integer'],
			[['name', 'description', 'safe_delete'], 'safe'],
			[['value'], 'number'],
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
		$query = Coupon::find()->where(['safe_delete' => false]);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
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
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'     => $this->id,
			'value'  => $this->value,
			'status' => $this->status,
		]);
		
		$query->andFilterWhere(['ilike', 'name', $this->name])
		      ->andFilterWhere(['ilike', 'description', $this->description]);
		
		return $dataProvider;
	}
}
