<?php


namespace api\models\forms;

use common\models\services\WebinarService;
use yii\base\Model;

class WebinarUpdateForm extends Model
{
    public $id;
    public $link;
    public $date;
    public $status;

    public function rules()
    {
        return [
            [['link', 'status'], 'string'],
            [['date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['id'], 'integer'],
            [['id'], 'required'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return WebinarService::updateWebinar($this->id, $this->link, $this->date, $this->status);
    }
}