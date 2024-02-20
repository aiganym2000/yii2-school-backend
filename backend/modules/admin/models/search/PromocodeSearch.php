<?php

namespace backend\modules\admin\models\search;

use common\models\entity\Promocode;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PromocodeSearch represents the model behind the search form of `common\models\entity\Promocode`.
 */
class PromocodeSearch extends Promocode
{
    public $from_date;
    public $to_date;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['percent'], 'number'],
            [['promo', 'created_at', 'updated_at', 'from_date', 'to_date'], 'safe'],
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
        $query = Promocode::find();

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
            'percent' => $this->percent,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query
            ->andFilterWhere(['>=', 'created_at', $this->from_date ? $this->from_date . ' 00:00:00' : null])
            ->andFilterWhere(['<=', 'created_at', $this->to_date ? $this->to_date . ' 23:59:59' : null])
            ->andFilterWhere(['like', 'promo', $this->promo]);

        return $dataProvider;
    }
}
