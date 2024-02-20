<?php


namespace api\models\forms;

use common\models\services\UserService;
use yii\base\Model;

class UserUpdatePasswordForm extends Model
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
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return UserService::updateUser($this->userId, null, null, null, null, $this->oldPassword, $this->newPassword);
    }
}