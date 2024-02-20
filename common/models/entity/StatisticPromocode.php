<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "statistic_promocode".
 *
 * @property int $id
 * @property string $date Дата
 * @property string $promo Промокоды
 * @property int $count Количество
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 *
 * @property Promocode $promocode
 */
class StatisticPromocode extends BaseEntity
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statistic_promocode';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['count'], 'integer'],
            [['promo'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'DATE'),
            'promo' => Yii::t('app', 'PROMOCODE_ID'),
            'count' => Yii::t('app', 'COUNT'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }
}
