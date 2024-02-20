<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\AchievementUser;
use common\models\entity\Notification;
use common\models\entity\PurchasedWebinar;
use common\models\entity\User;
use common\models\helpers\FCMPushHelper;
use Yii;
use yii\db\ActiveRecord;

class PurchasedWebinarService extends PurchasedWebinar
{
    /**
     * @return array|ActiveRecord[]
     */
    public static function listWebinar($userId)
    {
        return self::findAll(['user_id' => $userId]);
    }

    public static function addWebinar($webinarId, $userId)
    {
        $user = User::findOne($userId);
        $webinar = new self();
        $webinar->user_id = $userId;
        $webinar->webinar_id = $webinarId;
        $webinar->price = isset($webinar->webinar->price) ? $webinar->webinar->price : 1;

        if (!$webinar->save())
            RequestHelper::exceptionModel($webinar);

        $webinarA = AchievementUser::findOne(['user_id' => $userId, 'type' => AchievementUser::TYPE_WEBINAR]);
        if (!$webinarA) {
            $letter = AchievementUser::find()
                ->where(['user_id' => $userId])
                ->orderBy(['achievement_id' => SORT_DESC])
                ->one();
            if ($letter)
                $achievementId = $letter->achievement_id + 1;
            else
                $achievementId = 1;

            $achievement = AchievementService::createAchievement($achievementId, $userId, $webinar->webinar->title, AchievementUser::TYPE_WEBINAR);
            $text = 'У вас новое достижение! Вы получили его за покупку билета на мероприятие!';
            $notification = NotificationService::createNotification('Новое достижение!', $text, $userId, Notification::STATUS_SEND);
            if ($user->f_token)
                $response = FCMPushHelper::sendNotification($notification->title, $notification->description, $user->f_token);

            $achievements = AchievementUser::find()
                ->where(['user_id' => $userId])
                ->andWhere(['type' => AchievementUser::TYPE_COURSE])
                ->count();
            if ($achievements == 9) {
                $text = 'Вы собрали слово. Теперь Возрождение с вами навсегда! Скоро вы получите бонус.';
                $notification = NotificationService::createNotification('Все достижения собраны!', $text, $userId, Notification::STATUS_SEND);
                if ($user->f_token)
                    $response = FCMPushHelper::sendNotification($notification->title, $notification->description, $user->f_token);
            }
        }

        $text = 'Поздравляем с покупкой билета на ' . strip_tags($webinar->webinar->title) . '! Мероприятие состоится ' . $webinar->webinar->date . '. Мы пришлём Вам ссылку за час до встречи.';
        $notification = NotificationService::createNotification('Покупка вебинара', $text, $userId, Notification::STATUS_SEND);
        if ($user->f_token)
            $response = FCMPushHelper::sendNotification($notification->title, $notification->description, $user->f_token);

        if (date('Y-m-d H:i:s', strtotime('+1 hour')) >= $webinar->webinar->date) {
            $params = Yii::$app->params;

            $text = 'Всего через час начнется ' . strip_tags($webinar->webinar->title) . '. Ждём вас по ссылке: ' . $webinar->webinar->link;
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

        return $webinar;
    }
}