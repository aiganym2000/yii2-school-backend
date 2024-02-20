<?php

namespace common\models\entity;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sms_report".
 *
 * @property int $id
 * @property string $email
 * @property string $code Код
 * @property int $status Статус
 * @property string $created_at Дата создания
 * @property string $updated_at Дата изменения
 */
class Report extends ActiveRecord
{
    const STATUS_SEND = 1;
    const STATUS_CONFIRMED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'code'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['email', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'EMAIL'),
            'code' => Yii::t('app', 'CODE'),
            'status' => Yii::t('app', 'STATUS'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
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
                'value' => function () {
                    return date('Y-m-d H:i:s');
                }
            ]
        ];
    }
}
