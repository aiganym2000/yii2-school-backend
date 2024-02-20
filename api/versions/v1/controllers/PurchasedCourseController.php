<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\PurchasedCourseAddForm;
use api\models\forms\PurchasedCourseListForm;
use api\models\helper\RequestHelper;
use Yii;
use yii\web\ConflictHttpException;

class PurchasedCourseController extends AuthBaseController
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionList()
    {
        $model = new PurchasedCourseListForm(Yii::$app->user->id);

        if (($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionAdd()
    {
        $model = new PurchasedCourseAddForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }
}