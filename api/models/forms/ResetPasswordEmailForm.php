<?php


namespace api\models\forms;


use api\models\helper\ErrorMsgHelper;
use common\models\entity\User;
use yii\base\Exception;
use yii\base\Model;
use yii\web\ConflictHttpException;

class ResetPasswordEmailForm extends Model
{
    public $password;
    public $token;

    /**
     * @var User
     */
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'token'], 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Resets password.
     * @return Model if password was reset.
     * @throws ConflictHttpException
     * @throws Exception
     */
    public function resetPassword()
    {
        $this->_user = User::findByPasswordResetToken($this->token);
        if (!$this->_user)
            throw new ConflictHttpException('Неправильный токен');

        $user = $this->_user;

        if (password_verify($this->password, $user->password_hash))
            throw new ConflictHttpException('Старый пароль совпадает с новым');

        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->status = User::STATUS_ACTIVE;

        if (!$user->save(false))
            throw new ConflictHttpException(ErrorMsgHelper::getErrorMsg($user));

        return $user;
    }
}