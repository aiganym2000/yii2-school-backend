<?php


namespace common\models\pay;

use Yii;

class CryptPay
{
    const INVOICE_STATUS_CREATED = 'created';
    const INVOICE_STATUS_PAID = 'paid';
    const INVOICE_STATUS_PARTIAL = 'partial';
    const INVOICE_STATUS_CANCELLED = 'cancelled';

    const STATUS_SUCCESS = 'success';
    const STATUS_FAIL = 'fail';
    public $url;
    public $apiKey;
    public $shopId;

    public function __construct()
    {
        $params = Yii::$app->params['pay']['crypt'];
        $this->url = $params['api_url'];
        $this->apiKey = $params['api_key'];
        $this->shopId = $params['shop_id'];
    }

    /**
     * @return array
     */
    public static function getInvoiceStatusList()
    {
        return [
            self::INVOICE_STATUS_CREATED => 'created',
            self::INVOICE_STATUS_PAID => 'paid',
            self::INVOICE_STATUS_PARTIAL => 'partial',
            self::INVOICE_STATUS_CANCELLED => 'cancelled',
        ];
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_SUCCESS => 'success',
            self::STATUS_FAIL => 'fail',
        ];
    }

    public function create($amount, $orderId = null, $currency = null, $email = null)
    {
        $query = '/v1/invoice/create';
        $params = [
            'shop_id' => $this->shopId,
            'amount' => $amount,
            'order_id' => $orderId,
            'currency' => $currency,
            'email' => $email,
        ];
        return self::query($query, $params, true);
    }

    private function query($query, $params, $post)
    {
        $url = $this->url . $query;
        $headers = [
            'Authorization: Token ' . $this->apiKey
        ];
        $params = array_filter($params, static function ($var) {
            return $var !== null;
        });

        if (!$post) {
            $url .= '?';
            $queryParams = '';
            foreach ($params as $key => $value) {
                if ($value != null)
                    $queryParams = $queryParams . '&' . $key . '=' . $value;
            }
            $url .= $queryParams;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, $post);
        if ($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        curl_close($ch);
        return json_decode($response, true);
    }

    public function info($uuid)
    {
        $query = '/v1/invoice/info';
        $params = [
            'uuid' => $uuid,
        ];
        return self::query($query, $params, false);
    }
}