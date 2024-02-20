<?php

namespace backend\modules\admin\models\search;

use common\models\entity\Webinar;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WebinarSearch represents the model behind the search form of `common\models\entity\Webinar`.
 */
class WebinarSearch extends Webinar
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'course_id', 'status', 'created_user_id'], 'integer'],
            [['link', 'date', 'created_at', 'updated_at', 'title'], 'safe'],
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
    public function search($params, $courseId = null)
    {
        $query = Webinar::find();

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
            'status' => $this->status,
            'created_user_id' => $this->created_user_id,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'date', $this->date]);

        if ($courseId != null) {
            $query->andFilterWhere([
                'course_id' => $courseId,
            ]);
        }

        return $dataProvider;
    }
}
