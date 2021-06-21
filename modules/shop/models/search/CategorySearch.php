<?php

namespace app\modules\shop\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\Category;

/**
 * CategorySearch represents the model behind the search form of `app\modules\shop\models\Category`.
 */
class CategorySearch extends Category
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'description', 'slug','status','parent_id'], 'safe'],
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
        $query = Category::find()->with(['images','products.images','parent'])->orderBy(['id' => SORT_DESC]);

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
            'id' => $this->id,
        ]);
	    $query->andFilterWhere([
		    'parent_id' => $this->parent_id,
	    ]);
	    $query->andFilterWhere([
		    'status' => $this->status,
	    ]);
        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'slug', $this->slug]);

        return $dataProvider;
    }
}
