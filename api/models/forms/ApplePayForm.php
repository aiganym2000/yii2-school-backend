<?php


namespace api\models\forms;

use common\models\services\UserService;
use yii\base\Model;

class ApplePayForm extends Model
{
    public $userId;
    public $payId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['payId'], 'required'],
            [['payId'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return UserService::payApple($this->userId, $this->payId);
    }
}