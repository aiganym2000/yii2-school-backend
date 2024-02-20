<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\Notification;
use common\models\entity\PurchasedCourse;
use common\models\entity\User;
use common\models\helpers\FCMPushHelper;
use yii\db\ActiveRecord;

class PurchasedCourseService extends PurchasedCourse
{
    /**
     * @return array|ActiveRecord[]
     */
    public static function listCourse($userId)
    {
        return self::findAll(['user_id' => $userId]);
    }

    public static function addCourse($courseId, $userId)
    {
        $user = User::findOne($userId);
        $course = new self();
        $course->user_id = $userId;
        $course->course_id = $courseId;
        $course->price = isset($course->course->price) ? $course->course->price : 1;

        if (!$course->save())
            RequestHelper::exceptionModel($course);

        $text = 'Поздравляем с покупкой курса ' . strip_tags($course->course->title) . '! Занятия уже доступны в личном кабинете.';
        $notification = NotificationService::createNotification('Покупка курса', $text, $userId, Notification::STATUS_SEND);
        if ($user->f_token)
            $response = FCMPushHelper::sendNotification($notification->title, $notification->description, $user->f_token);

        return $course;
    }

}