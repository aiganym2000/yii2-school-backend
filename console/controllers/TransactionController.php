<?php

namespace console\controllers;

use common\models\entity\Setting;
use common\models\entity\Transaction;
use yii\console\Controller;

class TransactionController extends Controller
{
    public function actionIndex()
    {
        $limit = 20;
        $settings = Setting::findOne(['key' => 'pay_limit']);
        if ($settings) $limit = (int)$settings->value;

        $transactions = Transaction::find()
            ->where(['status' => Transaction::STATUS_CREATED])
            ->andWhere(['<=', 'created_at', date('Y-m-d H:i:s', strtotime('-' . $limit . ' minutes'))])
            ->all();
        foreach ($transactions as $transaction) {
            $transaction->status = Transaction::STATUS_CANCELLED;
            $transaction->save();
        }

        $this->stdout('Done!' . PHP_EOL);
    }
}