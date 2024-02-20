<?php

namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\entity\Transaction;
use common\models\pay\CryptPay;
use common\models\services\TransactionService;
use yii\base\Model;

class CpPostbackForm extends Model
{
    public $PaRes;
    public $TransactionId;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['status', 'invoice_id'], 'string'],
            [['status', 'invoice_id'], 'required'],
            ['status', 'in', 'range' => array_values(CryptPay::getStatusList())],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $transaction = Transaction::findOne(['pay_id' => $this->invoice_id, 'status' => Transaction::STATUS_IN_WAITING]);
        if (!$transaction)
            return RequestHelper::exception('Транзакция не найдена');

        TransactionService::updateStatusCrypt($transaction);
        return true;
    }
}