<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\LessonCourseListForm;
use api\models\forms\LessonCreateForm;
use api\models\forms\LessonListForm;
use api\models\forms\LessonUpdateForm;
use api\models\forms\LessonViewForm;
use api\models\helper\RequestHelper;
use common\models\helpers\ImageHelper;
use Yii;
use yii\web\ConflictHttpException;

class LessonController extends AuthBaseController
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCreate()
    {
        $model = new LessonCreateForm(Yii::$app->user->id);
        $video = ImageHelper::getVideo('video', 'video');
        $model->video = $video['path'];

        if (!$model->load(Yii::$app->request->post(), '') || ($lesson = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($lesson);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionUpdate()
    {
        $model = new LessonUpdateForm();
        $video = ImageHelper::getVideo('video', 'video');
        $model->video = $video['path'];

        if (!$model->load(Yii::$app->request->post(), '') || ($lesson = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($lesson);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionView()
    {
        $model = new LessonViewForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($lesson = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($lesson);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionList()
    {
        $model = new LessonListForm();

        if (($lesson = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($lesson);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCourseList()
    {
        $model = new LessonCourseListForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($lesson = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($lesson);
    }
}