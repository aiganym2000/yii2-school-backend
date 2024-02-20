<?php


namespace api\models\forms;

use common\models\services\AchievementService;
use yii\base\Model;

class AchievementListForm extends Model
{
    public $userId;

    public function __construct($userId, $status = null)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return AchievementService::listAchievement($this->userId);
    }
}