<?php


namespace api\models\forms;

use common\models\services\WebinarService;
use yii\base\Model;

class WebinarListForm extends Model
{
    public function save()
    {
        if (!$this->validate())
            return false;

        return WebinarService::listWebinar();
    }
}