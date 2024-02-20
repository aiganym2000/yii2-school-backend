<?php

namespace backend\modules\admin\models\search;

use common\models\entity\PurchasedCourse;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PurchasedCourseSearch represents the model behind the search form of `common\models\entity\PurchasedCourse`.
 */
class PurchasedCourseSearch extends PurchasedCourse
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'course_id'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
    public function search($params, $userId)
    {
        $query = PurchasedCourse::find();

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
            'course_id' => $this->course_id,
            'price' => $this->price,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);

        if ($userId != null) {
            $query->andFilterWhere([
                'user_id' => $userId,
            ]);
        }

        return $dataProvider;
    }
}
