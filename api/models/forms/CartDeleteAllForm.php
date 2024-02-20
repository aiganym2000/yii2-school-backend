<?php


namespace api\models\forms;

use common\models\services\UserService;
use yii\base\Model;

class CartDeleteAllForm extends Model
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

        return UserService::deleteCartAll($this->userId);
    }
}