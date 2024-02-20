<?php


namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\entity\User;
use Yii;
use yii\base\Model;
use yii\web\ConflictHttpException;

class LoginPhoneForm extends Model
{
    public $login;
    public $password;
    public $ftoken;
    public $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['login', 'password'], 'required'],
            [['ftoken'], 'default', 'value' => null],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user)
                return RequestHelper::exception('Пользователь не найден');

            if (!$user->validatePassword(trim($this->password)))
                return RequestHelper::exception('Неверный логин или пароль');

        }
    }

    /**
     * @return User|null
     */
    private function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByPhone(trim($this->login));
        }

        return $this->_user;
    }

    /**
     * @param null $attributeNames
     * @param null $clearErrors
     * @return bool
     * @throws ConflictHttpException
     */
    public function validate($attributeNames = null, $clearErrors = null)
    {
        if (!parent::validate($attributeNames, $clearErrors)) {
            return false;
        }

        if ($this->_user && $this->_user->status == User::STATUS_NOT_ACTIVE) {
            throw new ConflictHttpException(Yii::t('api', 'Пользователь не активирован'));
        }
        return true;
    }
}