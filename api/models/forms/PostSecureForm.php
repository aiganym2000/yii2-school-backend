<?php

namespace api\models\forms;


use api\models\helper\RequestHelper;
use common\models\services\TransactionService;
use yii\base\Model;

class PostSecureForm extends Model
{
    public $PaRes;
    public $MD;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PaRes', 'MD'], 'required'],
            [['PaRes', 'MD'], 'string'],
        ];
    }

    public function save()
    {
//        if (!$this->validate())
//            return false;
//        RequestHelper::exception($this);
        return TransactionService::createPostSecure($this->PaRes, $this->MD);
    }
}