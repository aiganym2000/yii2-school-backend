<?php

namespace api\models\forms;

use common\models\services\TransactionService;
use common\models\services\UserService;
use yii\base\Model;

class StripeCreateForm extends Model
{
    public $userId;
    public $email;

    /**
     * StripeCreateForm constructor.
     * @param $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    /**
     * @return array|bool
     * @throws \Stripe\Exception\ApiErrorException
     * @throws \yii\web\ConflictHttpException
     */
    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return TransactionService::createPayStripe($user);
    }
}