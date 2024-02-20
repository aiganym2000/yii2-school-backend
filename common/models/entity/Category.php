<?php

namespace common\models\entity;

use vova07\fileapi\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $title Название
 * @property string $description Описание
 * @property string $photo Фото
 * @property int $created_user Создан пользователем
 * @property int $status Статус
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 *
 * @property User $createdUser
 */
class Category extends BaseEntity
{
    const IMG_PATH = 'category';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    public function beforeDelete()
    {
        $id = $this->id;

        $courses = Course::findAll(['category_id' => $id]);
        foreach ($courses as $course) {
            $course->delete();
        }

        return parent::beforeDelete();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'status', 'photo'], 'required'],
            [['description'], 'string'],
            [['created_user', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['created_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_user' => 'id']],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'description',
            'photo' => function (self $model) {
                return $model->getImgUrl();
            },
            'created_user' => function (self $model) {
                return ($table = $model->getCreatedUser()->one()) ? $table->getData() : '';
            },
            'status' => function (self $model) {
                return $model->getStatusLabel();
            },
            'created_at',
            'updated_at',
        ];
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
    public function getImgPath()
    {
        return Yii::$app->params['staticDomain'] . '/images/' . self::IMG_PATH . '/';
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_user']);
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
            'photo' => Yii::t('app', 'PHOTO'),
            'created_user' => Yii::t('app', 'CREATED_USER'),
            'status' => Yii::t('app', 'STATUS'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }
}
