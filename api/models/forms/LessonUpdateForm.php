<?php


namespace api\models\forms;

use common\models\services\LessonService;
use yii\base\Model;

class LessonUpdateForm extends Model
{
    public $id;
    public $title;
    public $description;
    public $status;
    public $courseId;
    public $video;
    public $position;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['status', 'title', 'description'], 'string'],
            [['position', 'courseId', 'id'], 'integer'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
//            ['video', 'safe'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return LessonService::updateLesson($this->id, $this->title, $this->description, $this->status, $this->courseId, $this->position, $this->video);
    }
}