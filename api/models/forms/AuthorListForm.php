<?php


namespace api\models\forms;

use common\models\services\AuthorService;
use yii\base\Model;

class AuthorListForm extends Model
{
    public function save()
    {
        if (!$this->validate())
            return false;

        return AuthorService::listAuthor();
    }
}