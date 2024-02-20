<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\AchievementListForm;
use api\models\helper\RequestHelper;
use Yii;
use yii\web\ConflictHttpException;

class AchievementController extends AuthBaseController
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionList()
    {
        $model = new AchievementListForm(Yii::$app->user->id);

        if (($achievement = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($achievement);
    }
}