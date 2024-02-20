<?php


namespace api\models\forms;

use common\models\services\TransactionService;
use common\models\services\UserService;
use yii\base\Model;

class CpWidgetCreateForm extends Model
{
    public $transactionId;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['transactionId'], 'required'],
            [['transactionId'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return TransactionService::createPayCpWidget($this->transactionId, $user);
    }
}