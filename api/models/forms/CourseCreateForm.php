<?php


namespace api\models\forms;

use common\models\services\CourseService;
use common\models\services\UserService;
use yii\base\Model;

class CourseCreateForm extends Model
{
    public $title;
    public $description;
    public $photo;
    public $status;
    public $userId;
    public $categoryId;
    public $authorId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['categoryId', 'authorId', 'status', 'title'], 'required'],
            [['status', 'title', 'description'], 'string'],
            [['categoryId', 'authorId'], 'integer'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return CourseService::createCourse($this->title, $this->description, $this->status, $this->categoryId, $this->authorId, $this->photo, $this->userId);
    }
}