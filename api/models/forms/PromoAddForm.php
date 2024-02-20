<?php


namespace api\models\forms;

use common\models\services\UserService;
use yii\base\Model;

class PromoAddForm extends Model
{
    public $promocode;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

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

        return UserService::addPromo($this->userId, $this->promocode);
    }
}