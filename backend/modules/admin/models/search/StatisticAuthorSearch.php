<?php

namespace backend\modules\admin\models\search;

use common\models\entity\StatisticAuthor;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * StatisticAuthorSearch represents the model behind the search form of `common\models\entity\StatisticAuthor`.
 */
class StatisticAuthorSearch extends StatisticAuthor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'count'], 'integer'],
            [['sum'], 'number'],
            [['date', 'created_at', 'updated_at'], 'safe'],
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
        $dateFrom = Yii::$app->session->get('dateFrom');
        $dateTo = Yii::$app->session->get('dateTo');

        $query = StatisticAuthor::find()
            ->orderBy(['date' => SORT_DESC]);
        if ($dateFrom)
            $query->andWhere(['>=', 'date', $dateFrom]);
        if ($dateTo)
            $query->andWhere(['<=', 'date', $dateTo]);

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
            'author_id' => $this->author_id,
            'count' => $this->count,
            'sum' => $this->sum,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }
}
