<?php


namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\services\AuthorService;
use common\models\services\UserService;
use yii\base\Model;

class TokenForm extends Model
{
    public $f_token;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['f_token'], 'required'],
            [['f_token'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $user = UserService::getActiveUser($this->userId);
        $user->f_token = $this->f_token;
        if(!$user->save())
            RequestHelper::exceptionModel($user);

        return true;
    }
}