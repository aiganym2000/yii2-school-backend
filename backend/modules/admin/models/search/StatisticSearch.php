<?php

namespace backend\modules\admin\models\search;

use common\models\entity\Statistic;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * StatisticSearch represents the model behind the search form of `common\models\entity\Statistic`.
 */
class StatisticSearch extends Statistic
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['date', 'promocode_json', 'author_json', 'created_at', 'updated_at'], 'safe'],
            [['average_check'], 'number'],
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
        $query = Statistic::find()
            ->orderBy(['date' => SORT_DESC]);

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
            'date' => $this->date,
            'average_check' => $this->average_check,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'promocode_json', $this->promocode_json])
            ->andFilterWhere(['like', 'author_json', $this->author_json])
            ->andFilterWhere(['>=', 'date', date('Y-m-d H:i:s', strtotime('-30 days'))])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }
}
