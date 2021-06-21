<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

namespace app\modules\user\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\User;

/**
 * UserSearch represents the model behind the search form of `app\modules\user\models\User`.
 */
class UserSearch extends User
{
	
	public $search;
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'zip', 'created_at', 'updated_at', 'last_login_at'], 'integer'],
			[['email', 'password', 'auth_key', 'role', 'first_name', 'last_name', 'phone', 'address', 'city', 'state', 'bio', 'search'], 'safe'],
			[['blocked', 'confirmed'], 'boolean'],
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
		$query = User::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		
		$this->load($params);
		
		if(!$this->validate()) {
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'        => $this->id,
			'blocked'   => $this->blocked,
			'confirmed' => $this->confirmed,
			'zip'       => $this->zip,
		]);
		
		$query->andFilterWhere(['ilike', 'email', $this->email])
		      ->andFilterWhere(['ilike', 'password', $this->password])
		      ->andFilterWhere(['ilike', 'auth_key', $this->auth_key])
		      ->andFilterWhere(['ilike', 'role', $this->role])
		      ->andFilterWhere(['ilike', 'first_name', $this->first_name])
		      ->andFilterWhere(['ilike', 'last_name', $this->last_name])
		      ->andFilterWhere(['ilike', 'phone', $this->phone])
		      ->andFilterWhere(['ilike', 'address', $this->address])
		      ->andFilterWhere(['ilike', 'city', $this->city])
		      ->andFilterWhere(['ilike', 'state', $this->state])
		      ->andFilterWhere(['ilike', 'bio', $this->bio]);
		
		return $dataProvider;
	}
	
	/**
	 * @param $params
	 *
	 * @return ActiveDataProvider
	 */
	public function searchProvider($params)
	{
		$query = User::find()->where(['role' => 'distributor'])->with('store');
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		
		$this->load($params);
		
		
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'        => $this->id,
		]);
		
		$query->andFilterWhere(['ilike', 'email', $this->email]);
		
		return $dataProvider;
	}
}
