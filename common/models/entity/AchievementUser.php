<?php

namespace common\models\entity;

use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "achievement_user".
 *
 * @property int $id
 * @property int $achievement_id
 * @property int $user_id
 * @property string $for
 * @property int $type
 * @property string $created_at
 *
 * @property Achievement $achievement
 * @property User $user
 */
class AchievementUser extends ActiveRecord
{
    const TYPE_COURSE = 1;
    const TYPE_WEBINAR = 2;
    const TYPE_ALL = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'achievement_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['achievement_id', 'user_id', 'type'], 'required'],
            [['achievement_id', 'user_id', 'type'], 'integer'],
            [['created_at'], 'safe'],
            [['for'], 'string', 'max' => 255],
            [['achievement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Achievement::className(), 'targetAttribute' => ['achievement_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
                'value' => date('Y-m-d H:i:s'),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'letter' => function (self $model) {
                return $model->achievement->letter;
            },
            'title' => function (self $model) {
                return $model->achievement->title;
            },
            'description' => function (self $model) {
                return $model->achievement->description;
            },
            'for',
            'type',
            'created_at' => function (self $model) {
                return date('d.m.Y', strtotime($model->created_at));
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'achievement_id' => Yii::t('app', 'ACHIEVEMENT_ID'),
            'user_id' => Yii::t('app', 'USER_ID'),
            'for' => Yii::t('app', 'FOR'),
            'type' => Yii::t('app', 'TYPE'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
        ];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getTypeLabel()
    {
        return ArrayHelper::getValue(static::getTypeList(), $this->type);
    }

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_COURSE => Yii::t('app', 'TYPE_COURSE'),
            self::TYPE_WEBINAR => Yii::t('app', 'TYPE_WEBINAR'),
            self::TYPE_ALL => Yii::t('app', 'TYPE_ALL'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAchievement()
    {
        return $this->hasOne(Achievement::className(), ['id' => 'achievement_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
