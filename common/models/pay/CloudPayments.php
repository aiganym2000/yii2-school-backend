<?php

namespace common\models\pay;

//https://developers.cloudpayments.ru Документация
use api\models\helper\RequestHelper;
use Yii;
use yii\base\InvalidConfigException;

class CloudPayments
{
    /**
     * Для проверки взаимодействия с API
     * @return array
     */
    public static function test()
    {
        $params = [];
        return self::query($params, 'test');
    }

    /**
     * Построитель запросов
     * @param $params
     * @param $address
     * @return array
     */
    private static function query($params, $address, $post = false, $webhook = false)
    {
        $pay = Yii::$app->params['pay']['cloud-payments'];

        $queryParams = '';
        if (!$post) {
            foreach ($params as $key => $value) {
                if ($value != null) {
                    $queryParams = $queryParams . '&' . $key . '=' . urlencode($value);
                }
            }
            $headers = [
                'Content-Type: application/x-www-form-urlencoded'
            ];
        } else {
            $headers = [
                'Content-Type: application/json'
            ];
        }
        $url = $pay['url'] . $address . '?' . $queryParams;
        if ($webhook)
            $url = $address;


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, sprintf('%s:%s', $pay['login'], $pay['password']));
        curl_setopt($ch, CURLOPT_POST, true);
        if ($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        curl_close($ch);
        return json_decode($response, true);
    }

    /*
     * @param $params
     * @param $address
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */

    /**
     * Отмена оплаты
     * @param $transactionId int Номер транзакции
     * @return array
     */
    public static function cancel($transactionId)
    {
        $params = [
            'TransactionId' => $transactionId
        ];
        return self::query($params, 'payments/void');
    }

    /**
     * Возврат денег
     * @param $transactionId int Номер транзакции
     * @param $amount number Сумма возврата в валюте транзакции
     * @param null $jsonData string Любые другие данные, которые будут связаны с транзакцией
     * @return array
     */
    public static function refund($transactionId, $amount, $jsonData = null)
    {
        $params = [
            'TransactionId' => $transactionId,
            'Amount' => $amount,
            'JsonData' => $jsonData
        ];
        return self::query($params, 'payments/refund');
    }

    /**
     * Метод получения детализации по транзакции
     * @param $transactionId int Номер транзакции
     * @return array
     */
    public static function get($transactionId)
    {
        $params = [
            'TransactionId' => $transactionId
        ];
        return self::query($params, 'payments/get');
    }

    /**
     * Метод поиска платежа и проверки статуса
     * @param $invoiceId integer Номер заказа
     * @return array
     */
    public static function checkStatus($invoiceId)
    {
        $params = [
            'InvoiceId' => $invoiceId
        ];
        return self::query($params, 'v2/payments/find');
    }

    /**
     * Метод выгрузки списка транзакций за день
     * @param $date string Дата создания операций (Y-m-d)
     * @param $timeZone string Код временной зоны
     * @return array
     */
    public static function paymentList($date, $timeZone = 'ALMT')
    {
        $params = [
            'Date' => $date,
            'TimeZone' => $timeZone
        ];
        return self::query($params, 'payments/list');
    }

    /**
     * Метод выгрузки списка всех платежных токенов CloudPayments
     * @return array
     */
    public static function tokenList()
    {
        $params = [];
        return self::query($params, 'payments/tokens/list');
    }

    /**
     * @param $amount number Сумма платежа
     * @param $ipAddress string IP-адрес плательщика
     * @param $name string Имя держателя карты в латинице
     * @param $cardCryptogramPacket string Криптограмма платежных данных
     * @param $invoiceId string Номер счета или заказа
     * @param string $currency string Валюта: RUB/USD/EUR/KZT
     * @param null $description string Описание оплаты в свободной форме
     * @param null $paymentUrl string Адрес сайта, с которого совершается вызов скрипта checkout
     * @param null $cultureName string Язык уведомлений. Возможные значения: "ru-RU", "en-US"
     * @param null $accountId string Идентификатор пользователя
     * @param null $email string E-mail плательщика, на который будет отправлена квитанция об оплате
     * @param null $payer object Доп. поле, куда передается информация о плательщике. Используйте следующие параметры:
     * FirstName, LastName, MiddleName, Birth, Street, Address, City, Country, Phone, Postcode
     * @param null $jsonData string Любые другие данные, которые будут связаны с транзакцией. Используйте следующие параметры:
     * name, firstName, middleName, lastName, nick, phone, address, comment, birthDate
     * @return array
     */
    public static function pay($amount, $ipAddress, $name, $cardCryptogramPacket, $email = null, $invoiceId = null,
                               $currency = 'RUB', $description = null, $paymentUrl = null, $cultureName = null,
                               $accountId = null, $payer = null, $jsonData = null)
    {
        $params = [
            'Amount' => $amount,
            'Currency' => $currency,
            'IpAddress' => $ipAddress,
            'Name' => $name,
            'CardCryptogramPacket' => $cardCryptogramPacket,
            'InvoiceId' => $invoiceId,
            'Description' => $description,
            'PaymentUrl' => $paymentUrl,
            'CultureName' => $cultureName,
            'AccountId' => $accountId,
            'Email' => $email,
            'Payer' => $payer,
            'JsonData' => $jsonData
        ];
        return self::query($params, 'payments/cards/charge');
    }

    /**
     * @param $amount number Сумма платежа
     * @param $ipAddress string IP-адрес плательщика
     * @param $name string Имя держателя карты в латинице
     * @param $cardCryptogramPacket string Криптограмма платежных данных
     * @param $invoiceId string Номер счета или заказа
     * @param string $currency string Валюта: RUB/USD/EUR/KZT
     * @param null $description string Описание оплаты в свободной форме
     * @param null $paymentUrl string Адрес сайта, с которого совершается вызов скрипта checkout
     * @param null $cultureName string Язык уведомлений. Возможные значения: "ru-RU", "en-US"
     * @param null $accountId string Идентификатор пользователя
     * @param null $email string E-mail плательщика, на который будет отправлена квитанция об оплате
     * @param null $payer object Доп. поле, куда передается информация о плательщике. Используйте следующие параметры:
     * FirstName, LastName, MiddleName, Birth, Street, Address, City, Country, Phone, Postcode
     * @param null $jsonData string Любые другие данные, которые будут связаны с транзакцией. Используйте следующие параметры:
     * name, firstName, middleName, lastName, nick, phone, address, comment, birthDate
     * @return array
     */
    public static function payWithHold($amount, $ipAddress, $name, $cardCryptogramPacket, $email = null, $invoiceId = null,
                                       $currency = 'RUB', $description = null, $paymentUrl = null, $cultureName = null,
                                       $accountId = null, $payer = null, $jsonData = null)
    {
        $params = [
            'Amount' => $amount,
            'Currency' => $currency,
            'IpAddress' => $ipAddress,
            'Name' => $name,
            'CardCryptogramPacket' => $cardCryptogramPacket,
            'InvoiceId' => $invoiceId,
            'Description' => $description,
            'PaymentUrl' => $paymentUrl,
            'CultureName' => $cultureName,
            'AccountId' => $accountId,
            'Email' => $email,
            'Payer' => $payer,
            'JsonData' => $jsonData
        ];
        return self::query($params, 'payments/cards/auth');
    }

    /**
     * Подтверждение оплаты для платежей, проведенных по двухстадийной схеме
     * @param $transactionId int Номер транзакции
     * @param $amount number Сумма подтверждения в валюте транзакции
     * @param $jsonData string Сумма подтверждения в валюте транзакции
     * @return array
     */
    public static function confirm($transactionId, $amount, $jsonData = null)
    {
        $params = [
            'TransactionId' => $transactionId,
            'Amount' => $amount,
            'JsonData' => $jsonData
        ];
        return self::query($params, 'payments/confirm');
    }

    /**
     * Для проведения 3-D Secure аутентификации
     * @param $transactionId int Номер транзакции
     * @param $paRes string Значение одноименного параметра
     * @return array
     * @throws InvalidConfigException
     */
    public static function post3ds($transactionId, $paRes)
    {
        $params = [
            'TransactionId' => $transactionId,
            'PaRes' => $paRes
        ];
        return self::query2($params, 'payments/cards/post3ds');
    }

    private static function query2($params, $address)
    {
        $pay = Yii::$app->params['pay']['cloud-payments'];

        $url = $pay['url'] . $address;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERPWD, sprintf('%s:%s', $pay['login'], $pay['password']));
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2);

        $result = curl_exec($curl);

        curl_close($curl);


        $n = (array)json_decode($result, true);
        return $n;
//        RequestHelper::exception(print_r($n,true));

//        $client = new Client();
//        $response = $client->createRequest()
//            ->setMethod('post')
//            ->setUrl($url)
//            ->setData($params)
//            ->send();

//        return json_decode($response, true);
    }

    /**
     * Чек
     * @param $customerReceipt array Чек
     * @param $invoiceId number Идентификатор заказа
     * @param $accountId string Идентификатор пользователя
     * @param $type string Тип
     * @return array
     */
    public static function receipt($customerReceipt, $invoiceId, $accountId, $type = 'Income')
    {
        $params = [
            'Inn' => Yii::$app->params['pay']['cloud-payments']['inn'],
            'Type' => $type,
            'CustomerReceipt' => $customerReceipt,
            'InvoiceId' => $invoiceId,
            'AccountId' => $accountId,
        ];

        RequestHelper::info($params);

        return self::query($params, 'kkt/receipt', true);
    }

    /**
     * Чек
     * @param $customerReceipt array Чек
     * @param $invoiceId number Идентификатор заказа
     * @param $accountId string Идентификатор пользователя
     * @param $type string Тип
     * @return array
     */
    public static function receiptWebhook($customerReceipt, $invoiceId, $accountId, $type = 'Income')
    {
        $params = [
            'Inn' => Yii::$app->params['pay']['cloud-payments']['inn'],
            'Type' => $type,
            'CustomerReceipt' => $customerReceipt,
            'InvoiceId' => $invoiceId,
            'AccountId' => $accountId,
        ];
        return self::query($params, 'https://webhook.site/c006dabf-d99f-48a5-a236-b62858b858ee', true, true);
    }
}