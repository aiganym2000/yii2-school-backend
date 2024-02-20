<?php

namespace common\repository;

use api\models\helper\RequestHelper;
use common\models\entity\Transaction;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property string $amount Цена
 * @property string $pay_id Идентификатор транзакции в cloud payments
 * @property string $payment_type
 * @property string $cart
 * @property int $promocode_id
 * @property int $user_id
 * @property int $status Статус
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 */
class TransactionRepository extends Transaction
{
    public static function create($sum, $cart, $userId, $promocodeId, $type)
    {
        $transaction = new self();
        $transaction->amount = $sum;
        $transaction->status = self::STATUS_CREATED;
        $transaction->payment_type = $type;
        $transaction->cart = json_encode($cart);
        $transaction->user_id = $userId;
        $transaction->promocode_id = $promocodeId;
        if (!$transaction->save())
            return RequestHelper::exceptionModel($transaction);

        return $transaction;
    }
}