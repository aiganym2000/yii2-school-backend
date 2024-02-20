<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\TimingForm;
use api\models\forms\TokenForm;
use api\models\forms\UserDeleteForm;
use api\models\forms\UserUpdateForm;
use api\models\forms\UserUpdatePasswordForm;
use api\models\forms\UserUpdatePhoneForm;
use api\models\forms\UserViewForm;
use api\models\helper\RequestHelper;
use common\models\helpers\ImageHelper;
use Yii;
use yii\web\ConflictHttpException;

class UserController extends AuthBaseController
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionUpdate()
    {
        $model = new UserUpdateForm(Yii::$app->user->id);
        $ava = ImageHelper::getImage('ava', 'images/ava');
        $model->ava = $ava['path'];

        $model->load(Yii::$app->request->post(), '');
        if (($user = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($user->getData());
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionUpdatePhone()
    {
        $model = new UserUpdatePhoneForm(Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post(), '') && ($user = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($user->getData());
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionUpdatePassword()
    {
        $model = new UserUpdatePasswordForm(Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post(), '') && ($user = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($user->getData());
    }

    public function actionAddToken()
    {
        $model = new TokenForm(Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post(), '') && ($user = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success();
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionView()
    {
        $model = new UserViewForm(Yii::$app->user->id);

        if (($user = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($user);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionDelete()
    {
        $model = new UserDeleteForm(Yii::$app->user->id);

        if (($user = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($user);
    }

    public function actionTiming()
    {
        $model = new TimingForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($user = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($user);
    }
}