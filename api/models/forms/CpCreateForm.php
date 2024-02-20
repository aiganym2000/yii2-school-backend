<?php


namespace api\models\forms;


use common\models\services\TransactionService;
use common\models\services\UserService;
use yii\base\Model;

class CpCreateForm extends Model
{
    public $userId;
    public $name;
    public $cardCryptogramPacket;
    public $email;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['userId'], 'integer'],
            [['name', 'cardCryptogramPacket'], 'string'],
            [['email'], 'email'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return TransactionService::createPayCp($user, $this->name, $this->cardCryptogramPacket, $this->email);
    }
}