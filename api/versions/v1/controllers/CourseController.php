<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\CourseCreateForm;
use api\models\forms\CourseListForm;
use api\models\forms\CourseSearchForm;
use api\models\forms\CourseUpdateForm;
use api\models\forms\CourseViewForm;
use api\models\forms\SimilarCourseForm;
use api\models\helper\RequestHelper;
use common\models\helpers\ImageHelper;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\web\ConflictHttpException;

class CourseController extends AuthBaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'except' => [
                'list',
                'similar',
                'search',
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
        $model = new CourseCreateForm(Yii::$app->user->id);
        $photo = ImageHelper::getImage('photo', 'images/course');
        $model->photo = $photo['path'];

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionUpdate()
    {
        $model = new CourseUpdateForm();
        $photo = ImageHelper::getImage('photo', 'images/course');
        $model->photo = $photo['path'];

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionView()
    {
        $model = new CourseViewForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionList()
    {
        $model = new CourseListForm();

        if (($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionListAuth()
    {
        $model = new CourseListForm();

        if (($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionSimilar()
    {
        $model = new SimilarCourseForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionSearch()
    {
        $model = new CourseSearchForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionSearchAuth()
    {
        $model = new CourseSearchForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }
}