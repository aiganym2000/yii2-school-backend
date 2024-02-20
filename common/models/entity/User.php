<?php

namespace common\models\entity;

use api\models\helper\RequestHelper;
use Exception;
use vova07\fileapi\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $fullname
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $phone
 * @property string $auth_key
 * @property string $f_token
 * @property string $ref_string
 * @property string $ava
 * @property string $cart
 * @property string $reset_password_time
 * @property int $ref_count
 * @property bool $full_access
 * @property integer $status
 * @property integer $role
 * @property integer $promocode_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends BaseEntity implements IdentityInterface
{
    const IMG_PATH = 'ava';

    const STATUS_DELETED = 0;
    const STATUS_REGISTRATION = 5;
    const STATUS_ACTIVE = 10;
    const ROLE_SUPER_ADMIN = 0;
    const ROLE_ADMIN = 1;
    const ROLE_MODERATOR = 2;
    const ROLE_USER = 3;
    const ACCESS_OFF = 0;
    const ACCESS_ON = 1;
    public $password_current;
    public $password_new;
    public $password_new_repeat;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by phone
     *
     * @param string $phone
     * @return static|null
     */
    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @return int|string|null
     */
    public static function getCount()
    {
        return self::find()->count();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
            ->where(['id' => (string)$token->getClaim('uid')])
            ->andWhere(['status' => self::STATUS_ACTIVE])  //adapt this to your needs
            ->one();
    }

    /**
     * @return string
     */
    public static function generateCode()
    {
        $keySpace = '0123456789';
        $str = '';
        $max = mb_strlen($keySpace, '8bit') - 1;
        for ($i = 0; $i < 6; ++$i) {
            $str .= $keySpace[mt_rand(0, $max)];
        }
        return $str;
    }

    public function beforeDelete()
    {
        $id = $this->id;

        $models = AchievementUser::findAll(['user_id' => $id]);
        foreach ($models as $model)
            $model->delete();
        $models = Notification::findAll(['user_id' => $id]);
        foreach ($models as $model)
            $model->delete();
        $models = PurchasedCourse::findAll(['user_id' => $id]);
        foreach ($models as $model)
            $model->delete();
        $models = PurchasedWebinar::findAll(['user_id' => $id]);
        foreach ($models as $model)
            $model->delete();
        $models = RefferralUser::findAll(['user_id' => $id]);
        foreach ($models as $model)
            $model->delete();
        $models = RefferralUser::findAll(['ref_user_id' => $id]);
        foreach ($models as $model)
            $model->delete();
        $models = Timing::findAll(['user_id' => $id]);
        foreach ($models as $model)
            $model->delete();
        $models = UserAnswer::findAll(['user_id' => $id]);
        foreach ($models as $model)
            $model->delete();
        $models = UserLessons::findAll(['user_id' => $id]);
        foreach ($models as $model)
            $model->delete();
        $models = UserRefreshToken::findAll(['urf_userID' => $id]);
        foreach ($models as $model)
            $model->delete();

        $models = Author::findAll(['created_user' => $id]);
        foreach ($models as $model) {
            $model->created_user = null;
            $model->save();
        }
        $models = Banner::findAll(['created_user_id' => $id]);
        foreach ($models as $model) {
            $model->created_user_id = null;
            $model->save();
        }
        $models = Category::findAll(['created_user' => $id]);
        foreach ($models as $model) {
            $model->created_user = null;
            $model->save();
        }
        $models = Course::findAll(['created_user' => $id]);
        foreach ($models as $model) {
            $model->created_user = null;
            $model->save();
        }
        $models = Lesson::findAll(['created_user_id' => $id]);
        foreach ($models as $model) {
            $model->created_user_id = null;
            $model->save();
        }
        $models = Question::findAll(['created_user_id' => $id]);
        foreach ($models as $model) {
            $model->created_user_id = null;
            $model->save();
        }
        $models = Transaction::findAll(['user_id' => $id]);
        foreach ($models as $model) {
            $model->user_id = null;
            $model->save();
        }
        $models = Webinar::findAll(['created_user_id' => $id]);
        foreach ($models as $model) {
            $model->created_user_id = null;
            $model->save();
        }

        return parent::beforeDelete();
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id',
            'fullname',
            'email',
            'phone',
            'role' => function (self $model) {
                return $model->getRoleLabel();
            },
            'status' => function (self $model) {
                return $model->getStatusLabel();
            },
            'created_at' => function (self $model) {
                return date('Y-m-d H:i:s', $model->created_at);
            },
            'updated_at' => function (self $model) {
                return date('Y-m-d H:i:s', $model->updated_at);
            },
            'ava' => function (self $model) {
                return $model->getImgUrl();
            },
        ];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getRoleLabel()
    {
        return ArrayHelper::getValue(static::getRoleList(), $this->role);
    }

    /**
     * @return array
     */
    public static function getRoleList()
    {
        return [
            self::ROLE_SUPER_ADMIN => Yii::t('app', 'ROLE_SUPER_ADMIN'),
            self::ROLE_ADMIN => Yii::t('app', 'ROLE_ADMIN'),
            self::ROLE_MODERATOR => Yii::t('app', 'ROLE_MODERATOR'),
            self::ROLE_USER => Yii::t('app', 'ROLE_USER'),
        ];
    }

    /**
     * @return string
     */
    public function getImgUrl()
    {
        return $this->ava ? $this->getImgPath() . $this->ava : Yii::$app->params['staticDomain'] . '/default.png';
    }

    /**
     * @return string
     */
    public function getImgPath()
    {
        return Yii::$app->params['staticDomain'] . '/images/' . self::IMG_PATH . '/';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'ava' => [
                        'path' => '@static/web/images/' . self::IMG_PATH . '/',
                        'tempPath' => '@static/temp/',
                        'url' => $this->getImgPath()
                    ],
                ]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_REGISTRATION, self::STATUS_DELETED]],
            [['status', 'role', 'email'], 'required'],
            ['email', 'email'],
            [['ref_count', 'promocode_id'], 'integer'],
            ['full_access', 'boolean'],
            [['f_token', 'ref_string', 'ava', 'cart', 'fullname'], 'string'],
            [['phone', 'email'], 'unique'],
            ['password_new_repeat', 'compare', 'compareAttribute' => 'password_new'],
            ['password_current', 'findPasswords'],
            [['password_current', 'password_new', 'password_new_repeat', 'reset_password_time'], 'safe'],
            [['promocode_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promocode::className(), 'targetAttribute' => ['promocode_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'USERNAME'),
            'password_hash' => Yii::t('app', 'PASSWORD_HASH'),
            'password_reset_token' => Yii::t('app', 'PASSWORD_RESET_TOKEN'),
            'email' => Yii::t('app', 'EMAIL'),
            'ava' => Yii::t('app', 'AVA'),
            'auth_key' => Yii::t('app', 'AUTH_KEY'),
            'status' => Yii::t('app', 'STATUS'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
            'password' => Yii::t('app', 'PASSWORD'),
            'f_token' => Yii::t('app', 'F_TOKEN'),
            'phone' => Yii::t('app', 'PHONE'),
            'fullname' => Yii::t('app', 'FULLNAME'),
            'role' => Yii::t('app', 'ROLE'),
            'city_id' => Yii::t('app', 'CITY_ID'),
            'access_token' => Yii::t('app', 'ACCESS_TOKEN'),
            'password_new' => Yii::t('app', 'PASSWORD_NEW'),
            'ref_string' => Yii::t('app', 'REF_STRING'),
            'ref_count' => Yii::t('app', 'REF_COUNT'),
            'full_access' => Yii::t('app', 'FULL_ACCESS'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @param $attribute
     * @param $params
     * @return string
     * @throws \yii\base\Exception
     */
    public function findPasswords($attribute, $params)
    {
//        print_r($this->validatePassword($this->password_current)); die;
        if ($this->validatePassword($this->password_current)) {
            return $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_new);
        } else {
            return RequestHelper::exception('Старый пароль введен неверно');
        }
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'fullname' => $this->fullname,
            'phone' => $this->phone,
            'ava' => $this->getImgUrl(),
        ];
    }
}
