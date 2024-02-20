<?php

namespace api\controllers;


use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Controller;
use yii\web\ConflictHttpException;

class AuthBaseController extends Controller
{
    public $enableCsrfValidation = false;
    public $userId = null;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
        ];

        return $behaviors;
    }

    /**
     * Получение декодированных данных заголовка
     * @return array
     */
    public function getDecodedHeaderData()
    {
        $headerData = Yii::$app->request->getHeaders();
        $data = [];
        foreach ($headerData as $key => $header) {
            $data[$key] = $this->decodeData($header[0]);
        }
        return $data;
    }

    /**
     * Декодирование данных
     * @param $data
     * @return bool|string
     */
    public function decodeData($data)
    {
        return $data ? base64_decode($data) : false;
    }

    /**
     * Получение декодированных данных тела
     * @return array
     * @throws InvalidConfigException
     */
    public function getDecodedBodyData()
    {
        $bodyData = Yii::$app->request->getBodyParams();
        $data = [];
        foreach ($bodyData as $key => $header) {
            $data[$key] = $this->decodeData($header);
        }
        return $data;
    }

    /**
     * Декодирование POST запроса
     * @param $postData
     * @return array
     */
    public function decodePostData($postData)
    {
        $data = [];
        foreach ($postData as $k => $pD) {
            $data[$k] = $this->decodeData($pD);
        }
        return $data;
    }

    /**
     * Получение дополнительных данных
     * @return mixed
     */
    public function getSpecificData()
    {
        $request = Yii::$app->request;
        $data['ip'] = $request->userIP;
        $data['userData'] = $request->userAgent;
        return $data;
    }

    /**
     * Отключение API
     * @throws ConflictHttpException
     */
    private function disableApi()
    {
        throw new ConflictHttpException('Ведутся технические работы. Приносим извинения за доставленные неудобства.');
    }
}
