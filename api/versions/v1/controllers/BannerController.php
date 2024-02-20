<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\BannerCreateForm;
use api\models\forms\BannerListForm;
use api\models\forms\BannerUpdateForm;
use api\models\forms\BannerViewForm;
use api\models\helper\RequestHelper;
use common\models\helpers\ImageHelper;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\web\ConflictHttpException;

class BannerController extends AuthBaseController
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
        $model = new BannerCreateForm(Yii::$app->user->id);
        $photo = ImageHelper::getImage('path', 'images/banner');
        $model->path = $photo['path'];
        $model->size = $photo['size'];

        if (!$model->load(Yii::$app->request->post(), '') || ($banner = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($banner);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionUpdate()
    {
        $model = new BannerUpdateForm();
        $photo = ImageHelper::getImage('path', 'images/banner');
        $model->path = $photo['path'];
        $model->size = $photo['size'];

        if (!$model->load(Yii::$app->request->post(), '') || ($banner = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($banner);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionView()
    {
        $model = new BannerViewForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($banner = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($banner);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionList()
    {
        $model = new BannerListForm();

        if (($banner = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($banner);
    }
}