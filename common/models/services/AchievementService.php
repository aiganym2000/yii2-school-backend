<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\AchievementUser;

class AchievementService extends AchievementUser
{
    public static function listAchievement($userId)
    {
        return self::findAll(['user_id' => $userId]);
    }

    public static function createAchievement($achievementId, $userId, $for, $type)
    {
        $achievement = new self();
        $achievement->achievement_id = $achievementId;
        $achievement->user_id = $userId;
        $achievement->for = $for;
        $achievement->type = $type;

        if (!$achievement->save())
            RequestHelper::exceptionModel($achievement);

        return $achievement;
    }
}