<?php
namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return array
     */
    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ['status' => true];
    }

    /**
     *
     */
    public function actionPhpinfo() {
        echo phpinfo();
    }

//    public function actionCheckOrders($orderId)
//    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        if ($orderId){
//            $order = DeliveryOrder::findOne($orderId);
//            $result = DeliveryOrder::createCourierXlsx($order);
//            if ($result){
//                $this->sendMailModerator($orderId);
//                $this->sendMailRetail($order);
//                return ['status' => true];
//            } else {
//                $this->sendMailModeratorError($orderId);
//                return ['status' => true];
//            }
//        }
//        return ['status' => false];
//    }

}
