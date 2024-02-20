<?php


namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\services\AuthorService;
use yii\base\Model;

class AuthorViewForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return RequestHelper::exceptionModel($this);

        return AuthorService::viewAuthor($this->id);
    }
}