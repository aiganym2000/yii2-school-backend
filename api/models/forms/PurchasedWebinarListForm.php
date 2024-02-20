<?php


namespace api\models\forms;

use common\models\services\PurchasedWebinarService;
use yii\base\Model;

class PurchasedWebinarListForm extends Model
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

        return PurchasedWebinarService::listWebinar($this->userId);
    }
}