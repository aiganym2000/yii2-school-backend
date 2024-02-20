<?php

namespace common\models\entity;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "statistic_author".
 *
 * @property int $id
 * @property string $date Дата
 * @property int $author_id Спикеры
 * @property int $count Количество
 * @property number $sum Сумма
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 *
 * @property Author $author
 */
class StatisticAuthor extends BaseEntity
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statistic_author';
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
            [['author_id', 'count'], 'integer'],
            [['sum'], 'number'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::className(), 'targetAttribute' => ['author_id' => 'id']],
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
            'author_id' => Yii::t('app', 'AUTHOR_ID'),
            'count' => Yii::t('app', 'COUNT'),
            'sum' => Yii::t('app', 'SUM'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }
}
