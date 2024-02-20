<?php

namespace api\models\helper;

use Yii;
use yii\web\ConflictHttpException;

class RequestHelper
{
    /**
     * @param null $data
     * @param string $message
     * @return array
     */
    public static function success($data = null, $message = "")
    {
        return [
            'status' => 200,
            'type' => 'success',
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * @param null $data
     * @param string $message
     * @return array
     */
    public static function warning($data = null, $message = "")
    {
        return [
            'status' => 200,
            'type' => 'warning',
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * @param string $message
     * @throws ConflictHttpException
     */
    public static function exception($message = "Не найдено")
    {
        throw new ConflictHttpException($message);
    }

    /**
     * @param string $message
     */
    public static function info($message = "Не найдено") {
//        Yii::info($message, 'AMOCRM');
        Yii::info(print_r($message, true));

//        throw new SuccessHttpException($message);
    }

    /**
     * @param $model
     * @throws ConflictHttpException
     */
    public static function exceptionModel($model)
    {
        throw new ConflictHttpException(ErrorMsgHelper::getErrorMsg($model));
    }
}