<?php

namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\services\TransactionService;
use common\models\services\UserService;
use yii\base\Model;

class FaStripeCreateForm extends Model
{
    public $userId;
    public $email;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);
        if ($user->full_access)
            return RequestHelper::exception('Полный доступ уже имеется');

        return TransactionService::createPayStripe($user, true);
    }
}