<?php


namespace api\models\forms;

use common\models\services\BannerService;
use yii\base\Model;

class BannerUpdateForm extends Model
{
    public $id;
    public $title;
    public $path;
    public $size;
    public $position;
    public $zone;
    public $url;
    public $publishedAt;
    public $status;
    public $userId;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['id'], 'required'],
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

        return BannerService::updateBanner($this->id, $this->title, $this->path, $this->size, $this->position, $this->zone, $this->url, $this->status, $this->publishedAt);
    }
}