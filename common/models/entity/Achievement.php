<?php

namespace common\models\entity;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "achievement".
 *
 * @property int $id
 * @property string $letter
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class Achievement extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'achievement';
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['letter', 'title', 'description'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['letter'], 'string', 'max' => 1],
            [['title', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'letter' => Yii::t('app', 'LETTER'),
            'title' => Yii::t('app', 'TITLE'),
            'description' => Yii::t('app', 'DESCRIPTION'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }
}
