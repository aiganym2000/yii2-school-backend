<?php


namespace api\models\forms;

use common\models\services\UserService;
use yii\base\Model;

class PromoCheckForm extends Model
{
    public $promocode;

    public function rules()
    {
        return [
            [['promocode'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return UserService::checkPromo($this->promocode);
    }
}