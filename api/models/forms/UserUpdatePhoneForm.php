<?php


namespace api\models\forms;

use common\models\services\UserService;
use yii\base\Model;

class UserUpdatePhoneForm extends Model
{
    public $phone;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['phone'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return UserService::updateUser($this->userId, null, null, null, $this->phone);
    }
}