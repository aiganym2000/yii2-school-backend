<?php

namespace api\models\forms;


use api\models\helper\ErrorMsgHelper;
use common\models\services\UserService;
use yii\base\Model;
use yii\web\ConflictHttpException;

class ChangePasswordForm extends Model
{
    public $userId;
    public $oldPassword;
    public $newPassword;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['newPassword', 'oldPassword'], 'required'],
            [['newPassword', 'oldPassword'], 'string'],
        ];
    }

    public function save()
    {
        $user = UserService::getActiveUser($this->userId);

        if (!password_verify($this->oldPassword, $user->password_hash))
            throw new ConflictHttpException('Неверный пароль');

        $user->setPassword($this->newPassword);
        if (!$user->save())
            throw new ConflictHttpException(ErrorMsgHelper::getErrorMsg($user));

        return true;
    }
}