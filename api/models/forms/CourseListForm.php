<?php


namespace api\models\forms;

use common\models\services\CourseService;
use yii\base\Model;

class CourseListForm extends Model
{
    public function save()
    {
        if (!$this->validate())
            return false;

        return CourseService::listCourse();
    }
}