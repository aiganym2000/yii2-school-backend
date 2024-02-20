<?php


namespace api\models\forms;

use common\models\services\TransactionService;
use yii\base\Model;

class TypeListForm extends Model
{
    public function save()
    {
        if (!$this->validate())
            return false;

        return TransactionService::listType();
    }
}