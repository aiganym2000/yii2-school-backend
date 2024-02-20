<?php

namespace common\models\entity;

use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property string $amount Цена
 * @property string $pay_id Идентификатор транзакции в cloud payments
 * @property string $payment_type
 * @property string $cart
 * @property int $promocode_id
 * @property int $user_id
 * @property int $status Статус
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 *
 * @property User $user
 * @property Promocode $promocode
 */
class Transaction extends ActiveRecord
{
    const STATUS_CREATED = 1;
    const STATUS_PAID = 2;
    const STATUS_CANCELLED = 3;
    const STATUS_IN_WAITING = 4;

    const TYPE_CRYPT = 1;
    const TYPE_CLOUD_PAYMENTS = 2;
    const TYPE_STRIPE = 3;
    const TYPE_APPLE_PAY = 4;
    const TYPE_G_PAY = 5;

    const CART_TYPE_COURSE = 0;
    const CART_TYPE_OTHER = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'status'], 'required'],
            [['amount'], 'number'],
            [['status', 'promocode_id'], 'integer'],
            [['created_at', 'updated_at', 'pay_id', 'cart'], 'safe'],
            ['status', 'in', 'range' => [self::STATUS_CREATED, self::STATUS_PAID, self::STATUS_CANCELLED, self::STATUS_IN_WAITING]],
            ['payment_type', 'in', 'range' => array_keys(self::getTypeList())],
            [['promocode_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promocode::className(), 'targetAttribute' => ['promocode_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_CRYPT => Yii::t('app', 'TYPE_CRYPT'),
//            self::TYPE_CLOUD_PAYMENTS => Yii::t('app', 'TYPE_CLOUD_PAYMENTS'),
            self::TYPE_STRIPE => Yii::t('app', 'TYPE_STRIPE'),
            self::TYPE_APPLE_PAY => Yii::t('app', 'TYPE_APPLE_PAY'),
            self::TYPE_G_PAY => Yii::t('app', 'TYPE_G_PAY'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'amount' => Yii::t('app', 'AMOUNT'),
            'status' => Yii::t('app', 'STATUS'),
            'pay_id' => Yii::t('app', 'PAY_ID'),
            'cart' => Yii::t('app', 'CART'),
            'promocode_id' => Yii::t('app', 'PROMOCODE_ID'),
            'user_id' => Yii::t('app', 'USER_ID'),
            'payment_type' => Yii::t('app', 'PAYMENT_TYPE'),
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
                'value' => date('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * @return mixed
     * @throws Exception
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
            self::STATUS_CREATED => Yii::t('app', 'STATUS_CREATED'),
            self::STATUS_PAID => Yii::t('app', 'STATUS_PAID'),
            self::STATUS_CANCELLED => Yii::t('app', 'STATUS_CANCELLED'),
            self::STATUS_IN_WAITING => Yii::t('app', 'STATUS_IN_WAITING'),
        ];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getTypeLabel()
    {
        return ArrayHelper::getValue(static::getTypeList(), $this->payment_type);
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
    public function getPromocode()
    {
        return $this->hasOne(Promocode::className(), ['id' => 'promocode_id']);
    }
}
