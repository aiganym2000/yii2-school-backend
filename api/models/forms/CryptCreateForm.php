<?php

namespace api\models\forms;

use common\models\services\TransactionService;
use common\models\services\UserService;
use yii\base\Model;

class CryptCreateForm extends Model
{
    public $userId;
    public $email;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email'], 'email'],
            [['userId'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return TransactionService::createPayCrypt($user, $this->email);
    }
}