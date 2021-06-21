<?php

namespace app\modules\shop\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\CouponUser;

/**
 * CouponUserSearch represents the model behind the search form of `app\modules\shop\models\CouponUser`.
 */
class CouponUserSearch extends CouponUser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'count', 'coupon_id'], 'integer'],
            [['email'], 'safe'],
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
    public function search($params,$id)
    {
        $query = CouponUser::find()->where(['coupon_id' => $id]);

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
            'count' => $this->count,
            'coupon_id' => $this->coupon_id,
        ]);

        $query->andFilterWhere(['ilike', 'email', $this->email]);

        return $dataProvider;
    }
}
