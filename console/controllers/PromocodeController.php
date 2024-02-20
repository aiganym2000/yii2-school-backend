<?php

namespace console\controllers;

use common\models\entity\Promocode;
use common\models\entity\Transaction;
use yii\console\Controller;

class PromocodeController extends Controller
{
    public function actionIndex()
    {
        $promocodes = Promocode::findAll(['status' => Promocode::STATUS_ACTIVE]);
        foreach ($promocodes as $promocode) {
            $transaction = Transaction::findOne(['promocode_id' => $promocode->id, 'status' => Transaction::STATUS_PAID]);
            if ($transaction) {
                $promocode->count = 0;
                $promocode->status = Promocode::STATUS_INACTIVE;
                $promocode->save();
            }
        }

        $this->stdout('Done!' . PHP_EOL);
    }
}