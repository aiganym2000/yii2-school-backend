<?php

namespace common\models\entity;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "purchased_webinar".
 *
 * @property int $id
 * @property int $user_id Пользователь
 * @property int $webinar_id Вебинар
 * @property string $price Цена
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 *
 * @property User $user
 * @property Webinar $webinar
 */
class PurchasedWebinar extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchased_webinar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'webinar_id', 'price'], 'required'],
            [['user_id', 'webinar_id'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['webinar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Webinar::className(), 'targetAttribute' => ['webinar_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'webinar_id' => 'Webinar ID',
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
            'price',
            'user_id' => function (self $model) {
                return ($table = $model->getUser()->one()) ? $table->getData() : '';
            },
            'webinar_id' => function (self $model) {
                return ($table = $model->getWebinar()->one()) ? $table : '';
            },
            'created_at',
            'updated_at',
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
     * @return ActiveQuery
     */
    public function getWebinar()
    {
        return $this->hasOne(Webinar::className(), ['id' => 'webinar_id']);
    }
}
