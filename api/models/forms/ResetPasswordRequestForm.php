<?php


namespace api\models\forms;


use api\models\helper\RequestHelper;
use common\models\services\UserService;
use Yii;
use yii\base\Model;
use yii\web\ConflictHttpException;

class ResetPasswordRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            [['email'], 'string'],
            [['email'], 'required'],
        ];
    }

    /**
     * @return bool
     * @throws ConflictHttpException
     */
    public function save()
    {
        if (!$this->validate())
            RequestHelper::exceptionModel($this);

        $user = UserService::getUserByEmail($this->email);
        if ($user->reset_password_time && strtotime($user->reset_password_time . ' +5 min') > time())
            return RequestHelper::exception('Подождите 5 минут');

        $user->generatePasswordResetToken();
        $user->reset_password_time = date('Y-m-d H:i:s');
        $user->save();
        $params = Yii::$app->params;
        return Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
            ->setFrom([$params['robotEmail'] => $params['robotName']])
            ->setTo($this->email)
            ->setSubject('Сброс пароля')
            ->send();
    }
}