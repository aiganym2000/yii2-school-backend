<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\Course;
use common\models\entity\Promocode;
use common\models\entity\Transaction;
use common\models\entity\User;
use common\models\entity\Webinar;
use common\models\pay\CloudPayments;
use common\repository\TransactionRepository;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\ConflictHttpException;

class TransactionService extends Transaction
{
    public static function getSumFa($user, $type)
    {
        $result = UserService::fullAccess($user->id);
        $transaction = TransactionRepository::create(ceil($result['price']), 'full_access', $user->id, null, $type);

        return ['sum' => ceil($result['price']), 'cart' => null, 'transaction' => $transaction, 'promocode' => null];
    }

    /**
     * @param $user
     * @param $type
     * @return array|void
     * @throws ConflictHttpException
     */
    public static function getSumWithPromo($user, $type)
    {
        $promocode = Promocode::findOne(['id' => $user->promocode_id, 'status' => Promocode::STATUS_ACTIVE]);
        $cart = json_decode($user->cart, true);
        if (!$cart)
            return RequestHelper::exception('Корзина не найдена');

        $percent = 1;
        if ($promocode)
            $percent = (100 - $promocode->percent) / 100;
        $sum = self::getSum($cart, $percent);

        $transaction = TransactionRepository::create(ceil($sum), $cart, $user->id, $user->promocode_id, $type);

        return ['sum' => $sum, 'cart' => $cart, 'transaction' => $transaction, 'promocode' => $promocode];
    }

    public static function getSum($cart, $percent = 1)
    {
        $sum = 0;
        foreach ($cart as $item) {
            $item = explode('-', $item);
            if ($item[1] == self::CART_TYPE_COURSE)
                $model = Course::findOne($item[0]);
            else
                $model = Webinar::findOne($item[0]);

            if ($model)
                $sum += ceil((double)$model->price * $percent);
        }

        return $sum;
    }

    public static function purchaseFa($userId)
    {
        $user = User::findOne($userId);
        $user->full_access = User::ACCESS_ON;
        if (!$user->save())
            return RequestHelper::exceptionModel($user);
        return true;
    }

    public static function purchase($cart, $userId)
    {
        $user = User::findOne($userId);
        $promocode = Promocode::findOne(['id' => $user->promocode_id, 'status' => Promocode::STATUS_ACTIVE]);
        $user->promocode_id = null;
        $user->cart = null;
        $user->save();

        if ($promocode) {
            $promocode->count = 0;
            $promocode->status = Promocode::STATUS_INACTIVE;
            $promocode->save();
        }

        if ($cart) {
            foreach ($cart as $item) {
                $item = explode('-', $item);
                if ($item[1] == Transaction::CART_TYPE_COURSE) {
                    PurchasedCourseService::addCourse($item[0], $userId);
                } else {
                    PurchasedWebinarService::addWebinar($item[0], $userId);
                }
            }
        }
    }

    public static function createPayCpWidget($transactionId, $user)
    {
        $result = self::getSumWithPromo($user, self::TYPE_CLOUD_PAYMENTS);
        $transaction = $result['transaction'];
        $transaction->pay_id = $transactionId;

        if ($result['sum'] == 0) {
            $cart = json_decode($transaction->cart, true);
            if ($transaction->cart == '"full_access"')
                TransactionService::purchaseFa($transaction->user_id);
            else
                TransactionService::purchase($cart, $transaction->user_id);
            $transaction->status = Transaction::STATUS_PAID;
            $transaction->save();
            return true;
        }

        $info = CloudPayments::get($transactionId);
        if ($info['Success']) {
            $transaction->status = Transaction::STATUS_PAID;

            $cart = json_decode($transaction->cart, true);
            if ($transaction->cart == '"full_access"')
                TransactionService::purchaseFa($transaction->user_id);
            else
                TransactionService::purchase($cart, $transaction->user_id);
        }
        $transaction->save();

        return true;
    }

    public static function createPayCp($user, $name, $cardCryptogramPacket, $email = null, $fa = null)
    {
        if ($fa)
            $result = self::getSumFa($user, TransactionService::TYPE_CLOUD_PAYMENTS);
        else
            $result = self::getSumWithPromo($user, self::TYPE_CLOUD_PAYMENTS);
        $transaction = $result['transaction'];

        if ($result['sum'] == 0) {
            $cart = json_decode($transaction->cart, true);
            if ($transaction->cart == '"full_access"')
                TransactionService::purchaseFa($transaction->user_id);
            else
                TransactionService::purchase($cart, $transaction->user_id);
            $transaction->status = Transaction::STATUS_PAID;
            $transaction->save();
            return true;
        }

        if (!$cardCryptogramPacket)
            return RequestHelper::exception('Не заполнена криптограмма');

        $result = CloudPayments::pay($transaction->amount, Yii::$app->request->getUserIP(), $name, $cardCryptogramPacket, $email);

        $transaction->pay_id = $result['Model']['TransactionId'];
        if ($result['Success'])
            $transaction->status = Transaction::STATUS_PAID;

        if (!$transaction->save())
            return RequestHelper::exceptionModel($transaction);

        if ($result['Success']) {
            self::sendReceiptCp($transaction, $user, $email, $fa);
            $cart = json_decode($transaction->cart, true);
            if ($transaction->cart == '"full_access"')
                TransactionService::purchaseFa($transaction->user_id);
            else
                TransactionService::purchase($cart, $transaction->user_id);

            return true;
        } else {
            if (isset($result['Model']['CardHolderMessage']))
                return false;

            if (!$result['Message'])
                return $result['Model'];

            return RequestHelper::exception($result['Message']);
        }
    }

    /**
     * @param $paRes
     * @param $transactionId
     * @throws InvalidConfigException
     * @throws ConflictHttpException
     */
    public static function createPostSecure($paRes, $transactionId)
    {
        $pay = Yii::$app->params['pay']['cloud-payments'];
        $success = $pay['success_url'];
        $fail = $pay['cancel_url'];

        $transaction = Transaction::findOne(['pay_id' => $transactionId]);

        if (!$transaction)
            return $fail;

        $result = CloudPayments::post3ds($transactionId, $paRes);

        if (!$result['Success'])
            return $fail;

        $transaction->status = Transaction::STATUS_PAID;
        if (!$transaction->save())
            return $fail;

        $info = CloudPayments::get($transactionId);
        if (isset($info['Model']) && isset($info['Model']['Email'])) {
            $email = $info['Model']['Email'];
            if ($transaction->cart == '"full_access"')
                self::sendReceiptCp($transaction, $transaction->user, $email, true);
            else
                self::sendReceiptCp($transaction, $transaction->user, $email, false);
        }

        $cart = json_decode($transaction->cart, true);
        if ($transaction->cart == '"full_access"')
            TransactionService::purchaseFa($transaction->user_id);
        else
            TransactionService::purchase($cart, $transaction->user_id);

        return $success;
    }

    public static function updateStatusCrypt($transaction)
    {
        $crypt = new CryptPay();
        $info = $crypt->info('INV-' . $transaction->pay_id);
        if (isset($info['status']) && $info['status'] == CryptPay::STATUS_SUCCESS) {
            if ($info['status_invoice'] == CryptPay::INVOICE_STATUS_PAID) {
                $transaction->status = Transaction::STATUS_PAID;
                $transaction->save();

                $cart = json_decode($transaction->cart, true);
                if ($transaction->cart == '"full_access"')
                    TransactionService::purchaseFa($transaction->user_id);
                else
                    TransactionService::purchase($cart, $transaction->user_id);
            } elseif ($info['status_invoice'] == CryptPay::INVOICE_STATUS_CANCELLED) {
                $transaction->status = Transaction::STATUS_CANCELLED;
                $transaction->save();
            }
        }
    }

    public static function updateStatusCp($transaction)
    {
        $info = CloudPayments::get($transaction->pay_id);
        if ($info['Success']) {
            $transaction->status = Transaction::STATUS_PAID;
            $transaction->save();

            $cart = json_decode($transaction->cart, true);
            if ($transaction->cart == '"full_access"')
                TransactionService::purchaseFa($transaction->user_id);
            else
                TransactionService::purchase($cart, $transaction->user_id);
        }
    }
}