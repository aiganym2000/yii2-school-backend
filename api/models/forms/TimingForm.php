<?php


namespace api\models\forms;

use common\models\services\TimingService;
use common\models\services\UserService;
use yii\base\Model;

class TimingForm extends Model
{
    public $time;
    public $lessonId;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['time', 'lessonId'], 'required'],
            [['time', 'lessonId'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return TimingService::addTiming($this->lessonId, $this->time, $this->userId);
    }
}