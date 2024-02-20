<?php


namespace api\models\forms;

use common\models\services\PurchasedCourseService;
use yii\base\Model;

class PurchasedCourseListForm extends Model
{
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return PurchasedCourseService::listCourse($this->userId);
    }
}