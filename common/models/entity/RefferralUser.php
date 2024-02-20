<?php

namespace common\models\entity;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "refferral_user".
 *
 * @property int $id
 * @property int $ref_user_id
 * @property int $user_id
 *
 * @property User $refUser
 * @property User $user
 */
class RefferralUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'refferral_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ref_user_id', 'user_id'], 'required'],
            [['ref_user_id', 'user_id'], 'integer'],
            [['ref_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['ref_user_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ref_user_id' => Yii::t('app', 'Ref User ID'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getRefUser()
    {
        return $this->hasOne(User::className(), ['id' => 'ref_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
