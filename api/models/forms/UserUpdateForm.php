<?php


namespace api\models\forms;

use common\models\services\UserService;
use yii\base\Model;

class UserUpdateForm extends Model
{
    public $fullname;
    public $ava;
    public $email;
    public $phone;
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
            [['ava', 'fullname', 'newPassword', 'oldPassword', 'phone'], 'string'],
            [['email'], 'email'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);
        $user = UserService::getUniqueUserByEmail($this->email, $this->userId);

        return UserService::updateUser($this->userId, $this->fullname, $this->ava, $this->email, $this->phone, $this->oldPassword, $this->newPassword);
    }
}