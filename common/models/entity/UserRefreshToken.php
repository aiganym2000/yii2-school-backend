<?php

namespace common\models\entity;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_refresh_tokens".
 *
 * @property int $id
 * @property int $urf_userID
 * @property string $urf_token
 * @property string $urf_ip
 * @property string $urf_user_agent
 * @property string $urf_created
 */
class UserRefreshToken extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_refresh_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urf_userID', 'urf_token', 'urf_ip', 'urf_user_agent'], 'required'],
            [['urf_userID'], 'integer'],
            [['urf_created'], 'safe'],
            [['urf_token', 'urf_user_agent'], 'string', 'max' => 1000],
            [['urf_ip'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'urf_userID' => Yii::t('app', 'Urf User ID'),
            'urf_token' => Yii::t('app', 'Urf Token'),
            'urf_ip' => Yii::t('app', 'Urf Ip'),
            'urf_user_agent' => Yii::t('app', 'Urf User Agent'),
            'urf_created' => Yii::t('app', 'Urf Created'),
        ];
    }
}
