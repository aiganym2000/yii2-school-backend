<?php


namespace api\models\forms;

use common\models\services\CourseService;
use yii\base\Model;

class CourseUpdateForm extends Model
{
    public $id;
    public $title;
    public $description;
    public $photo;
    public $status;
    public $categoryId;
    public $authorId;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['status', 'title', 'description', 'id'], 'string'],
            [['categoryId', 'authorId'], 'integer'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return CourseService::updateCourse($this->id, $this->title, $this->description, $this->status, $this->categoryId, $this->authorId, $this->photo);
    }
}