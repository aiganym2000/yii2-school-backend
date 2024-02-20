<?php

namespace common\models\entity;

use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class main BaseEntity
 *
 * @property int $id
 * @property integer $status
 */
class BaseEntity extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;

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
            self::STATUS_DELETED => Yii::t('app', 'STATUS_DELETED'),
            self::STATUS_NOT_ACTIVE => Yii::t('app', 'STATUS_NOT_ACTIVE'),
            self::STATUS_ACTIVE => Yii::t('app', 'STATUS_ACTIVE'),
        ];
    }
}
