<?php

namespace backend\modules\admin\models\search;

use common\models\entity\Lesson;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LessonSearch represents the model behind the search form of `common\models\entity\Lesson`.
 */
class LessonSearch extends Lesson
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'course_id', 'position', 'status', 'created_user_id'], 'integer'],
            [['title', 'description', 'video', 'created_at', 'updated_at'], 'safe'],
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
        $query = Lesson::find()
            ->orderBy('position');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
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
            'position' => $this->position,
            'status' => $this->status,
            'created_user_id' => $this->created_user_id,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'video', $this->video]);

        if ($courseId != null) {
            $query->andFilterWhere([
                'course_id' => $courseId,
            ]);
        }

        return $dataProvider;
    }
}
