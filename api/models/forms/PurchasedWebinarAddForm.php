<?php


namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\services\PurchasedWebinarService;
use yii\base\Model;

class PurchasedWebinarAddForm extends Model
{
    public $webinarId;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['webinarId'], 'required'],
            [['webinarId'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        if (PurchasedWebinarService::findOne(['user_id' => $this->userId, 'webinar_id' => $this->webinarId])) {
            RequestHelper::exception("Вебинар уже куплен");
        }

        return PurchasedWebinarService::addWebinar($this->webinarId, $this->userId);
    }
}