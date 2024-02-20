<?php

namespace common\models\services;

use api\models\helper\ErrorMsgHelper;
use common\models\entity\Timing;
use yii\web\ConflictHttpException;

class TimingService extends Timing
{
    public static function addTiming($lessonId, $time, $userId)
    {
        $timing = Timing::findOne(['user_id' => $userId, 'lesson_id' => $lessonId]);
        if (!$timing) {
            $timing = new self();
            $timing->lesson_id = $lessonId;
            $timing->user_id = $userId;
        }

        $timing->time = $time;

        if (!$timing->save())
            throw new ConflictHttpException(ErrorMsgHelper::getErrorMsg($timing));

        return $timing;
    }
}