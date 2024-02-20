<?php


namespace api\models\forms;

use common\models\services\LessonService;
use yii\base\Model;

class LessonListForm extends Model
{
    public function save()
    {
        if (!$this->validate())
            return false;

        return LessonService::listLesson();
    }
}