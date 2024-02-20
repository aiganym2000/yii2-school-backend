<?php


namespace api\models\forms;

use common\models\services\QuestionService;
use yii\base\Model;

class QuestionListForm extends Model
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

        return QuestionService::getQuestions($this->courseId, $this->userId);
    }
}