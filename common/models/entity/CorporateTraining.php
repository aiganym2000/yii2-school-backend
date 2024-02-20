<?php

namespace common\models\entity;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "corporate_training".
 *
 * @property int $id
 * @property string $theme
 * @property string $name Имя
 * @property string $phone Телефон
 * @property string $email Email
 * @property string $text Текст
 * @property int $status Статус
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 */
class CorporateTraining extends BaseEntity
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'corporate_training';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'text', 'status', 'email'], 'required'],
            [['text', 'theme'], 'string'],
            [['email'], 'email'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'phone'], 'string', 'max' => 255],
        ];
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
        ];
    }

    public function fields()
    {
        return [
            'id',
            'theme',
            'name',
            'phone',
            'email',
            'text',
            'status' => function (self $model) {
                return $model->getStatusLabel();
            },
            'created_at',
            'updated_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'theme' => Yii::t('app', 'THEME'),
            'name' => Yii::t('app', 'NAME'),
            'phone' => Yii::t('app', 'PHONE'),
            'email' => Yii::t('app', 'EMAIL'),
            'text' => Yii::t('app', 'TEXT'),
            'status' => Yii::t('app', 'STATUS'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }
}
