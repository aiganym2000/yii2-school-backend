<?php


namespace api\models\forms;

use common\models\services\UserService;
use yii\base\Model;

class CartDeleteForm extends Model
{
    public $id;
    public $type;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['id', 'type'], 'required'],
            [['id', 'type'], 'integer'],
            [['type'], 'in', 'range' => [0, 1]],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);

        return UserService::deleteCart($this->id, $this->type, $this->userId);
    }
}