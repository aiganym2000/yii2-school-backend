<?php

namespace common\models\helpers;

use paragraph1\phpFCM\Recipient\Device;
use Yii;
use yii\web\ConflictHttpException;

class FCMPushHelper
{
    /**
     * @param $title
     * @param $description
     * @param $token
     * @return
     * @throws ConflictHttpException
     */
    public static function sendNotification($title, $description, $token)
    {
//        try {
        return FCMPushHelper::baseNotification($title, $description, $token);
//        } catch (Exception $e) {
//            RequestHelper::exception("MESSAGE_NOT_SEND");
//        }//todo
    }

    public static function baseNotification($title, $description, $token)
    {
        $message = Yii::$app->fcm->createMessage();
        $message->addRecipient(new Device($token));

        $note = Yii::$app->fcm->createNotification($title, $description);
        //$note->setIcon('notification_icon_resource_name')
        //  ->setColor('#ffffff');

        $message->setNotification($note);
        $response = Yii::$app->fcm->send($message);

        return $response->getStatusCode();
    }
}