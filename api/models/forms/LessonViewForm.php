<?php


namespace api\models\forms;

use common\models\services\LessonService;
use yii\base\Model;

class LessonViewForm extends Model
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

        return LessonService::viewLesson($this->id, $this->userId);
    }
}