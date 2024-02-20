<?php


namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\services\CourseService;
use yii\base\Model;

class SimilarCourseForm extends Model
{
    public $courseId;

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

        $course = CourseService::findOne($this->courseId);
        if (!$course)
            RequestHelper::exception('Курс не найден');

        return CourseService::findSimilar($course->id, $course->category_id, $course->author_id);
    }
}