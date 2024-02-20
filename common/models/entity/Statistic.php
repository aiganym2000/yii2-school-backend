<?php

namespace common\models\entity;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "statistic".
 *
 * @property int $id
 * @property string $date Дата
 * @property string $average_check Средний чек
 * @property string $promocode_json Промокоды
 * @property string $author_json Спикеры
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 */
class Statistic extends BaseEntity
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statistic';
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'average_check'], 'required'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['average_check'], 'number'],
            [['promocode_json', 'author_json'], 'string'],
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
            'average_check' => Yii::t('app', 'AVERAGE_CHECK'),
            'promocode_json' => Yii::t('app', 'PROMOCODE_JSON'),
            'author_json' => Yii::t('app', 'AUTHOR_JSON'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }
}
