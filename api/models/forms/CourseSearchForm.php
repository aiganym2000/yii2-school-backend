<?php


namespace api\models\forms;

use common\models\services\CourseService;
use yii\base\Model;

class CourseSearchForm extends Model
{
    public $title;

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return CourseService::findByName($this->title);
    }
}