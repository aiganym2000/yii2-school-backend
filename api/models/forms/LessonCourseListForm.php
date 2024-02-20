<?php

namespace api\models\forms;


use common\models\services\LessonService;
use yii\base\Model;

class LessonCourseListForm extends Model
{
    public $courseId;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['courseId'], 'required'],
            [['courseId'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return LessonService::listLesson($this->courseId);
    }
}