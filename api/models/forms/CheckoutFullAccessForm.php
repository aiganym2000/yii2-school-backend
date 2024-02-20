<?php


namespace api\models\forms;

use common\models\services\UserService;
use yii\base\Model;

class CheckoutFullAccessForm extends Model
{
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function save()
    {
        return UserService::checkoutFullAccess($this->userId);
    }
}