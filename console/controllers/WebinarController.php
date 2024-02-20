<?php

namespace console\controllers;

use common\models\entity\Notification;
use common\models\entity\PurchasedWebinar;
use common\models\entity\Webinar;
use common\models\helpers\FCMPushHelper;
use common\models\services\NotificationService;
use Yii;
use yii\console\Controller;

class WebinarController extends Controller
{
    public function actionIndex()
    {
        $webinars = Webinar::find()->where(['>=', 'date', date('Y-m-d H:i:s')])->all();
        $params = Yii::$app->params;

        foreach ($webinars as $webinar) {
            if (date('Y-m-d H:i:s', strtotime('+24 hour')) >= $webinar->date) {
                $pWebinars = PurchasedWebinar::findAll(['webinar_id' => $webinar->id]);
                foreach ($pWebinars as $pWebinar) {
                    $text = 'Вы приглашены на ' . strip_tags($webinar->title) . '. Осталось всего 24 часа! Будем ждать встречи.';
                    $user = $pWebinar->user;
                    $res = Yii::$app->mailer->compose()
                        ->setFrom([$params['robotEmail'] => $params['robotName']])
                        ->setTo($user->email)
                        ->setSubject('Скоро будет вебинар')
                        ->setTextBody($text)
                        ->send();

                    $notification = NotificationService::createNotification('Скоро будет вебинар', $text, $user->id, Notification::STATUS_SEND);
                    if ($user->f_token)
                        $response = FCMPushHelper::sendNotification($notification->title, $notification->description, $user->f_token);
                }
            }
        }

        $this->stdout('Done!' . PHP_EOL);
        return 0;
    }

    public function actionHour()
    {
        $webinars = Webinar::find()->where(['>=', 'date', date('Y-m-d H:i:s')])->all();
        $params = Yii::$app->params;

        foreach ($webinars as $webinar) {
            if (date('Y-m-d H:i:s', strtotime('+1 hour')) >= $webinar->date) {
                $pWebinars = PurchasedWebinar::findAll(['webinar_id' => $webinar->id]);
                foreach ($pWebinars as $pWebinar) {
                    $text = 'Всего через час начнется ' . strip_tags($webinar->title) . '. Ждём вас по ссылке: ' . $webinar->link;
                    $user = $pWebinar->user;
                    $res = Yii::$app->mailer->compose()
                        ->setFrom([$params['robotEmail'] => $params['robotName']])
                        ->setTo($user->email)
                        ->setSubject('Скоро будет вебинар')
                        ->setTextBody($text)
                        ->send();

                    $notification = NotificationService::createNotification('Скоро будет вебинар', $text, $user->id, Notification::STATUS_SEND);
                    if ($user->f_token)
                        $response = FCMPushHelper::sendNotification($notification->title, $notification->description, $user->f_token);
                }
            }
        }

        $this->stdout('Done!' . PHP_EOL);
        return 0;
    }

    public function actionFiveMin()
    {
        $webinars = Webinar::find()->where(['>=', 'date', date('Y-m-d H:i:s')])->all();
        $params = Yii::$app->params;

        foreach ($webinars as $webinar) {
            if (date('Y-m-d H:i:s', strtotime('+5 minutes')) >= $webinar->date) {
                $pWebinars = PurchasedWebinar::findAll(['webinar_id' => $webinar->id]);
                foreach ($pWebinars as $pWebinar) {
                    $text = 'Время подключаться! ' . $webinar->course->author->fio . ' ждёт вас.';
                    $user = $pWebinar->user;
                    $res = Yii::$app->mailer->compose()
                        ->setFrom([$params['robotEmail'] => $params['robotName']])
                        ->setTo($user->email)
                        ->setSubject('Скоро будет вебинар')
                        ->setTextBody($text)
                        ->send();

                    $notification = NotificationService::createNotification('Скоро будет вебинар', $text, $user->id, Notification::STATUS_SEND);
                    if ($user->f_token)
                        $response = FCMPushHelper::sendNotification($notification->title, $notification->description, $user->f_token);
                }
            }
        }

        $this->stdout('Done!' . PHP_EOL);
        return 0;
    }
}