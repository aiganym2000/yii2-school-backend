<?php


namespace api\models\forms;

use common\models\services\WebinarService;
use yii\base\Model;

class WebinarViewForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return WebinarService::viewWebinar($this->id);
    }
}