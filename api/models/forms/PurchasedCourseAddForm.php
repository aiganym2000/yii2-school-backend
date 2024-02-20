<?php


namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\services\PurchasedCourseService;
use yii\base\Model;

class PurchasedCourseAddForm extends Model
{
    public $courseId;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

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

        if (PurchasedCourseService::findOne(['user_id' => $this->userId, 'course_id' => $this->courseId])) {
            RequestHelper::exception("Курс уже куплен");
        }

        return PurchasedCourseService::addCourse($this->courseId, $this->userId);
    }
}