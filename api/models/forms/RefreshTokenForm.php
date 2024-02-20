<?php

namespace api\models\forms;

use common\models\services\AuthorService;
use common\models\services\UserService;
use yii\base\Model;

class RefreshTokenForm extends Model
{
    public $refreshToken;

    public function rules()
    {
        return [
            [['refreshToken'], 'required'],
            [['refreshToken'], 'string'],
        ];
    }
}