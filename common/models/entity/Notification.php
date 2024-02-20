<?php

namespace common\models\entity;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property string $title Название
 * @property string $description Описание
 * @property int $status Статус
 * @property int $user_id Пользователь
 * @property string $created_at Дата создания
 *
 * @property User $user
 */
class Notification extends ActiveRecord
{
    const STATUS_SEND = 1;
    const STATUS_READED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => function () {
                    return date("Y-m-d H:i:s");
                },
            ]
        ];

    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['status', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'description',
            'status',
            'created_user' => function (self $model) {
                return ($table = $model->getUser()->one()) ? $table->getData() : '';
            },
            'created_at',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
            'status' => Yii::t('app', 'STATUS'),
            'user_id' => Yii::t('app', 'USER_ID'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
        ];
    }

    /**
     * @return mixed
     */
    public function getStatusLabel()
    {
        return ArrayHelper::getValue(static::getStatusList(), $this->status);
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_SEND => Yii::t('app', 'STATUS_SEND'),
            self::STATUS_READED => Yii::t('app', 'STATUS_READED'),
        ];
    }
}
