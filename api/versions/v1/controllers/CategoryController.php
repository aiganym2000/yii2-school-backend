<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\CategoryCreateForm;
use api\models\forms\CategoryListForm;
use api\models\forms\CategoryUpdateForm;
use api\models\forms\CategoryViewForm;
use api\models\helper\RequestHelper;
use common\models\helpers\ImageHelper;
use Yii;
use yii\web\ConflictHttpException;

class CategoryController extends AuthBaseController
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCreate()
    {
        $model = new CategoryCreateForm(Yii::$app->user->id);
        $photo = ImageHelper::getImage('photo', 'images/category');
        $model->photo = $photo['path'];

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
        $model = new CategoryUpdateForm();
        $photo = ImageHelper::getImage('photo', 'images/category');
        $model->photo = $photo['path'];

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
        $model = new CategoryViewForm();

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
        $model = new CategoryListForm();

        if (($category = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($category);
    }
}