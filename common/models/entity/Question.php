<?php

namespace common\models\entity;

use common\models\helpers\SortableGridBehavior;
use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "question".
 *
 * @property int $id
 * @property int $course_id
 * @property string $text
 * @property int $position
 * @property int $type
 * @property string $answer
 * @property int $created_user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdUser
 * @property Course $course
 */
class Question extends ActiveRecord
{
    const TYPE_ONE_ANSWER = 1;
    const TYPE_SEVERAL_ANSWERS = 2;
    const TYPE_MATCH = 3;
    const TYPE_PLACEMENT = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question';
    }

    public function beforeDelete()
    {
        $id = $this->id;

        $models = UserAnswer::findAll(['question_id' => $id]);
        foreach ($models as $model) {
            $model->delete();
        }

        $questions = Question::find()
            ->where(['course_id' => $this->course_id])
            ->andWhere(['!=', 'id', $this->id])
            ->orderBy('position')
            ->all();
        $i = 1;
        foreach ($questions as $question) {
            $question->position = $i;
            $question->save();
            $i++;
        }

        return parent::beforeDelete();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'text', 'answer', 'type'], 'required'],
            [['course_id', 'position', 'type', 'created_user_id'], 'integer'],
            [['text', 'answer'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_user_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
            ['type', 'in', 'range' => [self::TYPE_ONE_ANSWER, self::TYPE_SEVERAL_ANSWERS, self::TYPE_MATCH, self::TYPE_PLACEMENT]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'COURSE_ID'),
            'text' => Yii::t('app', 'TEXT'),
            'position' => Yii::t('app', 'POSITION'),
            'type' => Yii::t('app', 'TYPE'),
            'answer' => Yii::t('app', 'ANSWER'),
            'created_user_id' => Yii::t('app', 'CREATED_USER'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }

    public function fields()
    {
        return [
            'id',
//            'course_id' => function (self $model) {
//                return ($table = $model->getCourse()->one()) ? $table : '';
//            },
            'text',
            'position',
            'type',
            'answered_status' => function (self $model) {
                return $model->getUserAnswers()
                    ->where(['user_id' => Yii::$app->user->id])
                    ->one() ? 1 : 0;
            },
            'answer' => function (self $model) {
                try {
                    $answer = json_decode($model->answer, true);
                    $array = [];
                    if ($model->type != self::TYPE_MATCH) {
                        $id = 1;
                        foreach ($answer as $an) {
                            $array[] = array_merge(['id' => $id], $an);
                            $id++;
                        }
                        $oldArray = $array;
                        if (count($array) > 1) {
                            do {
                                $same = false;
                                shuffle($array);
                                if ($array == $oldArray) {
                                    $same = true;
                                }
                            } while ($same);
                        }
                    } else {
                        $id = 1;
                        $first = $answer['first'];
                        $second = $answer['second'];
                        foreach ($second as $s) {
                            $array[] = array_merge(['id' => $id], $s);
                            $id++;
                        }
                        $oldArray = $array;
                        if (count($array) > 1) {
                            do {
                                $same = false;
                                shuffle($array);
                                if ($array == $oldArray) {
                                    $same = true;
                                }
                            } while ($same);
                        }

                        $array = [
                            'first' => $first,
                            'second' => $array,
                        ];
                    }
                    return $array;
                } catch (Exception $exception) {
                }
            },
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUserAnswers()
    {
        return $this->hasMany(UserAnswer::className(), ['question_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_user_id']);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getTypeLabel()
    {
        return ArrayHelper::getValue(static::getTypeList(), $this->type);
    }

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_ONE_ANSWER => Yii::t('app', 'TYPE_ONE_ANSWER'),
            self::TYPE_SEVERAL_ANSWERS => Yii::t('app', 'TYPE_SEVERAL_ANSWERS'),
            self::TYPE_MATCH => Yii::t('app', 'TYPE_MATCH'),
            self::TYPE_PLACEMENT => Yii::t('app', 'TYPE_PLACEMENT'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'sort' => [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'position',
                'attributeName' => 'course_id'
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }
}
