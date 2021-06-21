<?php

namespace app\modules\settings\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\settings\models\Zip;

/**
 * ZipSearch represents the model behind the search form of `app\modules\settings\models\Zip`.
 */
class ZipSearch extends Zip
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['zipcode', 'state'], 'safe'],
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
        $query = Zip::find()->orderBy(['id' => SORT_ASC]);

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
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['ilike', 'zipcode', $this->zipcode])
            ->andFilterWhere(['ilike', 'state', $this->state]);

        return $dataProvider;
    }
}
