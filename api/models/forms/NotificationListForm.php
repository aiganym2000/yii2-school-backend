<?php

namespace api\models\forms;

use common\models\services\NotificationService;
use yii\base\Model;

class NotificationListForm extends Model
{
    public $userId;
    public $status;

    public function __construct($userId, $status = null)
    {
        $this->userId = $userId;
        $this->status = $status;
        parent::__construct();
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return NotificationService::listNotification($this->userId, $this->status);
    }
}