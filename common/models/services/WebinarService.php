<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\Webinar;

class WebinarService extends Webinar
{
    public static function createWebinar($link, $date, $courseId, $status, $userId)
    {
        $webinar = new self();
        $webinar->link = $link;
        $webinar->date = $date;
        $webinar->course_id = $courseId;
        $webinar->created_user_id = $userId;
        if ($status == 'ACTIVE')
            $webinar->status = self::STATUS_ACTIVE;
        else
            $webinar->status = self::STATUS_NOT_ACTIVE;

        if (!$webinar->save())
            RequestHelper::exceptionModel($webinar);

        return $webinar;
    }

    public static function updateWebinar($id, $link = null, $date = null, $status = null)
    {
        $webinar = self::findOne($id);

        if (!$webinar)
            RequestHelper::exception();

        if ($link)
            $webinar->link = $link;

        if ($date)
            $webinar->date = $date;

        if ($status) {
            if ($status == 'ACTIVE')
                $webinar->status = self::STATUS_ACTIVE;
            else
                $webinar->status = self::STATUS_NOT_ACTIVE;
        }

        if (!$webinar->save())
            RequestHelper::exceptionModel($webinar);

        return $webinar;
    }

    public static function viewWebinar($id)
    {
        return ($model = self::findOne(['id' => $id])) ? $model : RequestHelper::exception();
    }

    public static function listWebinar()
    {
        return self::find()->all();
    }
}