<?php


namespace api\models\forms;

use common\models\services\CourseService;
use yii\base\Model;

class CourseViewForm extends Model
{
    public $id;

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

        return CourseService::viewCourse($this->id);
    }
}