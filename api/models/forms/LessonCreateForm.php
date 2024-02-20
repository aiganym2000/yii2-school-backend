<?php


namespace api\models\forms;

use common\models\services\LessonService;
use common\models\services\UserService;
use yii\base\Model;

class LessonCreateForm extends Model
{
    public $title;
    public $description;
    public $status;
    public $userId;
    public $courseId;
    public $video;
    public $position;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['status', 'title', 'position', 'courseId'], 'required'],
            [['status', 'title', 'description'], 'string'],
            [['position', 'courseId'], 'integer'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
//            ['video', 'safe'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return LessonService::createLesson($this->title, $this->description, $this->status, $this->courseId, $this->position, $this->userId, $this->video);
    }
}