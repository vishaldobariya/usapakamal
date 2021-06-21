<?php

namespace app\modules\shop\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `app\modules\shop\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['first_name', 'last_name', 'email', 'address', 'adress_two', 'city', 'contry', 'zip', 'phone', 'state', 'billing_address', 'billing_address_two', 'billing_city', 'billing_country', 'billing_state', 'billing_zip', 'billing_phone', 'billing_first_name', 'billing_last_name'], 'safe'],
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
        $query = Customer::find()->orderBy(['id' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['ilike', 'first_name', $this->first_name])
            ->andFilterWhere(['ilike', 'last_name', $this->last_name])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'address', $this->address])
            ->andFilterWhere(['ilike', 'adress_two', $this->adress_two])
            ->andFilterWhere(['ilike', 'city', $this->city])
            ->andFilterWhere(['ilike', 'contry', $this->contry])
            ->andFilterWhere(['ilike', 'zip', $this->zip])
            ->andFilterWhere(['ilike', 'phone', $this->phone])
            ->andFilterWhere(['ilike', 'state', $this->state])
            ->andFilterWhere(['ilike', 'billing_address', $this->billing_address])
            ->andFilterWhere(['ilike', 'billing_address_two', $this->billing_address_two])
            ->andFilterWhere(['ilike', 'billing_city', $this->billing_city])
            ->andFilterWhere(['ilike', 'billing_country', $this->billing_country])
            ->andFilterWhere(['ilike', 'billing_state', $this->billing_state])
            ->andFilterWhere(['ilike', 'billing_zip', $this->billing_zip])
            ->andFilterWhere(['ilike', 'billing_phone', $this->billing_phone])
            ->andFilterWhere(['ilike', 'billing_first_name', $this->billing_first_name])
            ->andFilterWhere(['ilike', 'billing_last_name', $this->billing_last_name]);

        return $dataProvider;
    }
}
