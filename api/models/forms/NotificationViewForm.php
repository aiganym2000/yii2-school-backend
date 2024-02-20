<?php

namespace api\models\forms;

use common\models\services\NotificationService;
use yii\base\Model;

class NotificationViewForm extends Model
{
    public $id;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return NotificationService::viewNotification($this->id, $this->userId);
    }
}