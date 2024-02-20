<?php


namespace api\models\forms;

use common\models\services\BannerService;
use common\models\services\UserService;
use yii\base\Model;

class BannerCreateForm extends Model
{
    public $title;
    public $path;
    public $size;
    public $position;
    public $zone;
    public $url;
    public $publishedAt;
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
            [['status', 'title'], 'required'],
            [['status', 'title', 'url'], 'string'],
            [['position', 'zone'], 'integer'],
            [['publishedAt'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return BannerService::createBanner($this->title, $this->path, $this->size, $this->position, $this->zone, $this->url, $this->status, $this->publishedAt, $this->userId);
    }
}