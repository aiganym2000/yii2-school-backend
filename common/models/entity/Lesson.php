<?php

namespace common\models\entity;

use common\models\helpers\SortableGridBehavior;
use vova07\fileapi\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "lesson".
 *
 * @property int $id
 * @property string $title Название
 * @property string $description Описание
 * @property string $video Видео
 * @property string $vimeo Вимео
 * @property int $course_id Курс
 * @property string $time Время
 * @property int $position Позиция
 * @property int $status Статус
 * @property int $created_user_id Создан пользователем
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 *
 * @property Course $course
 * @property User $createdUser
 */
class Lesson extends BaseEntity
{
    const VIDEO_PATH = 'video';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson';
    }

    public function beforeDelete()
    {
        $id = $this->id;

        $timings = Timing::findAll(['lesson_id' => $id]);
        foreach ($timings as $timing) {
            $timing->delete();
        }

        $lessons = Lesson::find()
            ->where(['course_id' => $this->course_id])
            ->andWhere(['!=', 'id', $this->id])
            ->orderBy('position')
            ->all();
        $i = 1;
        foreach ($lessons as $lesson) {
            $lesson->position = $i;
            $lesson->save();
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
            [['title', 'course_id', 'status'], 'required'],
            [['course_id', 'position', 'status', 'created_user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['time'], 'number'],
            [['title', 'description', 'video', 'vimeo'], 'string', 'max' => 255],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['created_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_user_id' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'title',
            'description',
            'course_id' => function (self $model) {
                return ($table = $model->getCourse()->one()) ? $table : '';
            },
            'position',
            'id',
            'vimeo',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_user_id']);
    }

    /**
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->video ? $this->getVideoPath() . $this->video : '';
    }

    /**
     * @return string
     */
    public function getVideoPath()
    {
        return Yii::$app->params['staticDomain'] . '/' . self::VIDEO_PATH . '/';
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
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'video' => [
                        'path' => '@static/web/video/',
                        'tempPath' => '@static/temp/',
                        'url' => $this->getVideoPath()
                    ],
                ]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'TITLE'),
            'description' => Yii::t('app', 'DESCRIPTION'),
            'video' => Yii::t('app', 'VIDEO'),
            'vimeo' => Yii::t('app', 'VIMEO'),
            'course_id' => Yii::t('app', 'COURSE_ID'),
            'position' => Yii::t('app', 'POSITION'),
            'status' => Yii::t('app', 'STATUS'),
            'created_user_id' => Yii::t('app', 'CREATED_USER'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }
}
