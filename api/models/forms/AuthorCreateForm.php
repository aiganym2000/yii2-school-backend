<?php


namespace api\models\forms;

use common\models\services\AuthorService;
use common\models\services\UserService;
use yii\base\Model;

class AuthorCreateForm extends Model
{
    public $fio;
    public $description;
    public $photo;
    public $status;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['status', 'fio'], 'required'],
            [['status', 'fio', 'description'], 'string'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return AuthorService::createAuthor($this->fio, $this->status, $this->description, $this->photo, $this->userId);
    }
}