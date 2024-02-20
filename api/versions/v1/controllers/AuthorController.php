<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\AuthorCreateForm;
use api\models\forms\AuthorListForm;
use api\models\forms\AuthorUpdateForm;
use api\models\forms\AuthorViewForm;
use api\models\helper\RequestHelper;
use common\models\helpers\ImageHelper;
use Yii;
use yii\web\ConflictHttpException;

class AuthorController extends AuthBaseController
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCreate()
    {
        $model = new AuthorCreateForm(Yii::$app->user->id);
        $photo = ImageHelper::getImage('photo', 'images/author');
        $model->photo = $photo['path'];

        if (!$model->load(Yii::$app->request->post(), '') || ($author = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($author);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionUpdate()
    {
        $model = new AuthorUpdateForm();
        $photo = ImageHelper::getImage('photo', 'images/author');
        $model->photo = $photo['path'];

        if (!$model->load(Yii::$app->request->post(), '') || ($author = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($author);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionView()
    {
        $model = new AuthorViewForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($author = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($author);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionList()
    {
        $model = new AuthorListForm();

        if (($author = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($author);
    }
}