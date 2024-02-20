<?php


namespace api\models\forms;

use common\models\services\UserService;
use yii\base\Model;

class UserDeleteForm extends Model
{
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return UserService::deleteUser($this->userId);
    }
}