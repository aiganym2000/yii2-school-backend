<?php

namespace common\models\entity;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "random_seed".
 *
 * @property int $id
 * @property int $random_seed
 */
class RandomSeed extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'random_seed';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['random_seed'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'random_seed' => Yii::t('app', 'Random Seed'),
        ];
    }
}
