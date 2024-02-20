<?php

namespace common\models\entity;

use vova07\fileapi\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "author".
 *
 * @property int $id
 * @property string $fio ФИО
 * @property string $description Описание
 * @property string $photo Фото
 * @property string $small_photo
 * @property string $video
 * @property string $vimeo Вимео
 * @property int $created_user Создан пользователем
 * @property int $status Статус
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 *
 * @property User $createdUser
 */
class Author extends BaseEntity
{
    const IMG_PATH = 'author';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'author';
    }

    public function beforeDelete()
    {
        $id = $this->id;

        $courses = Course::findAll(['author_id' => $id]);
        foreach ($courses as $course) {
            $course->delete();
        }
        $statistics = StatisticAuthor::findAll(['author_id' => $id]);
        foreach ($statistics as $statistic) {
            $statistic->delete();
        }

        return parent::beforeDelete();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s')
            ],
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'photo' => [
                        'path' => '@static/web/images/' . self::IMG_PATH . '/',
                        'tempPath' => '@static/temp/',
                        'url' => $this->getImgPath()
                    ],
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
     * @return string
     */
    public function getImgPath()
    {
        return Yii::$app->params['staticDomain'] . '/images/' . self::IMG_PATH . '/';
    }

    /**
     * @return string
     */
    public function getVideoPath()
    {
        return Yii::$app->params['staticDomain'] . '/video/';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'status', 'photo'], 'required'],
            [['created_user', 'status'], 'integer'],
            [['created_at', 'updated_at', 'description'], 'safe'],
            [['fio', 'photo', 'video', 'vimeo', 'small_photo'], 'string', 'max' => 255],
            [['created_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_user' => 'id']],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'fio',
            'description',
            'created_user' => function (self $model) {
                return ($table = $model->getCreatedUser()->one()) ? $table->getData() : '';
            },
            'status' => function (self $model) {
                return $model->getStatusLabel();
            },
            'photo' => function (self $model) {
                return $model->getImgUrl();
            },
            'small_photo' => function (self $model) {
                return $model->getSmallImgUrl();
            },
            'video' => function (self $model) {
                return $model->getVideoUrl();
            },
            'vimeo',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_user']);
    }

    /**
     * @return string
     */
    public function getImgUrl()
    {
        return $this->photo ? $this->getImgPath() . $this->photo : $this->getImgPath() . 'default.png';
    }

    /**
     * @return string
     */
    public function getSmallImgUrl()
    {
        return $this->small_photo ? $this->getImgPath() . $this->small_photo : $this->getImgPath() . 'default.png';
    }

    /**
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->video ? $this->getVideoPath() . $this->video : '';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fio' => Yii::t('app', 'FIO'),
            'photo' => Yii::t('app', 'PHOTO'),
            'small_photo' => Yii::t('app', 'SMALL_PHOTO'),
            'video' => Yii::t('app', 'VIDEO'),
            'vimeo' => Yii::t('app', 'VIMEO'),
            'description' => Yii::t('app', 'DESCRIPTION'),
            'created_user' => Yii::t('app', 'CREATED_USER'),
            'status' => Yii::t('app', 'STATUS'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }
}
