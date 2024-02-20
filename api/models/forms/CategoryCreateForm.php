<?php


namespace api\models\forms;

use common\models\services\CategoryService;
use common\models\services\UserService;
use yii\base\Model;

class CategoryCreateForm extends Model
{
    public $title;
    public $description;
    public $status;
    public $userId;
    public $photo;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['status', 'title'], 'required'],
            [['status', 'title', 'description'], 'string'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return CategoryService::createCategory($this->title, $this->description, $this->status, $this->photo, $this->userId);
    }
}