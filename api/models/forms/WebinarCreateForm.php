<?php


namespace api\models\forms;

use common\models\services\UserService;
use common\models\services\WebinarService;
use yii\base\Model;

class WebinarCreateForm extends Model
{
    public $link;
    public $date;
    public $courseId;
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
            [['link', 'date', 'courseId', 'status'], 'required'],
            [['link', 'status'], 'string'],
            [['courseId'], 'integer'],
            [['date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return WebinarService::createWebinar($this->link, $this->date, $this->courseId, $this->status, $this->userId);
    }
}