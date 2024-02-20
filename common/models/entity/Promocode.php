<?php

namespace common\models\entity;

use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "promocode".
 *
 * @property int $id
 * @property string $promo Промо Код
 * @property number $percent Сумма сертификата
 * @property int $status Статус
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 */
class Promocode extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = 2;
    public $count;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promocode';
    }

    public function beforeDelete()
    {
        $id = $this->id;

        $models = Transaction::findAll(['promocode_id' => $id]);
        foreach ($models as $model) {
            $model->promocode_id = null;
            $model->save();
        }
        $models = User::findAll(['promocode_id' => $id]);
        foreach ($models as $model) {
            $model->promocode_id = null;
            $model->save();
        }

        return parent::beforeDelete();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['count', 'promo', 'percent'], 'required'],
            [['status', 'count'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['promo'], 'string', 'max' => 255],
            [['promo'], 'trim'],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['percent', 'number', 'max' => 100],
            ['count', 'integer', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'promo' => Yii::t('app', 'PROMO'),
            'percent' => Yii::t('app', 'PERCENT'),
            'count' => Yii::t('app', 'COUNT'),
            'status' => Yii::t('app', 'STATUS'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }

    public function setPromo()
    {
        $this->promo = Yii::$app->security->generateRandomString(8);
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
            self::STATUS_INACTIVE => Yii::t('app', 'STATUS_INACTIVE'),
            self::STATUS_ACTIVE => Yii::t('app', 'STATUS_ACTIVE'),
        ];
    }
}
