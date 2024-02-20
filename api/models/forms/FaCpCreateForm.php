<?php


namespace api\models\forms;


use api\models\helper\RequestHelper;
use common\models\services\TransactionService;
use common\models\services\UserService;
use yii\base\Model;

class FaCpCreateForm extends Model
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
            [['userId', 'name', 'cardCryptogramPacket'], 'required'],
            [['userId'], 'integer'],
            [['name', 'cardCryptogramPacket', 'email'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);
        if ($user->full_access)
            return RequestHelper::exception('Полный доступ уже имеется');

        return TransactionService::createPayCp($user, $this->name, $this->cardCryptogramPacket, $this->email, true);
    }
}