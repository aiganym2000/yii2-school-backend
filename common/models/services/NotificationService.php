<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\Notification;

class NotificationService extends Notification
{
    /**
     * @param $waiterId
     * @param $userTableId
     * @param $type
     * @param $status
     * @return bool|Notification
     */
    public static function createNotification($title, $description, $userId, $status)
    {
        $notification = new Notification();
        $notification->user_id = $userId;
        $notification->title = $title;
        $notification->description = $description;
        $notification->status = $status;
        if (!$notification->save())
            RequestHelper::exceptionModel($notification);

        return $notification;
    }

    public static function viewNotification($id, $userId)
    {
        $model = self::findOne(['id' => $id, 'user_id' => $userId]);
        if (!$model)
            RequestHelper::exception();
        $model->status = Notification::STATUS_READED;
        $model->save();
        return $model;
    }

    public static function listNotification($userId, $status = null)
    {
        $notifications = self::find()
            ->where(['user_id' => $userId]);
        if ($status) {
            $notifications->where(['status' => $status]);
        }
        return $notifications->all();
    }
}