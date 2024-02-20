<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\WebinarCreateForm;
use api\models\forms\WebinarListForm;
use api\models\forms\WebinarUpdateForm;
use api\models\forms\WebinarViewForm;
use api\models\helper\RequestHelper;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\web\ConflictHttpException;

class WebinarController extends AuthBaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'except' => [
                'list',
                'view',
            ],
        ];

        return $behaviors;
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCreate()
    {
        $model = new WebinarCreateForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($category = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($category);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionUpdate()
    {
        $model = new WebinarUpdateForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($category = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($category);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionView()
    {
        $model = new WebinarViewForm();

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
        $model = new WebinarListForm();

        if (($category = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($category);
    }
}