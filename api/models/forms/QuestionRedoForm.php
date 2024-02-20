<?php


namespace api\models\forms;

use common\models\services\QuestionService;
use yii\base\Model;

class QuestionRedoForm extends Model
{
    public $lessonId;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['lessonId'], 'required'],
            [['lessonId'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return QuestionService::redoLesson($this->lessonId, $this->userId);
    }
}