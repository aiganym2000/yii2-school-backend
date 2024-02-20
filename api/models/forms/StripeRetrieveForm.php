<?php


namespace api\models\forms;

use common\models\services\TransactionService;
use yii\base\Model;

class StripeRetrieveForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return TransactionService::retrieveStripe($this->id);
    }
}