<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\NotificationListForm;
use api\models\forms\NotificationViewForm;
use api\models\helper\RequestHelper;
use common\models\entity\Notification;
use Yii;
use yii\web\ConflictHttpException;

class NotificationController extends AuthBaseController
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionView()
    {
        $model = new NotificationViewForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($category = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($category);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionList()
    {
        $model = new NotificationListForm(Yii::$app->user->id);

        if (($notification = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($notification);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionListNew()
    {
        $model = new NotificationListForm(Yii::$app->user->id, Notification::STATUS_SEND);

        if (($notification = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($notification);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionListRead()
    {
        $model = new NotificationListForm(Yii::$app->user->id, Notification::STATUS_READED);

        if (($notification = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($notification);
    }
}