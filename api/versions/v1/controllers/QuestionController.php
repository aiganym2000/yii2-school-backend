<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\QuestionCreateForm;
use api\models\forms\QuestionFinishForm;
use api\models\forms\QuestionListForm;
use api\models\forms\QuestionRedoForm;
use api\models\helper\RequestHelper;
use Yii;
use yii\web\ConflictHttpException;

class QuestionController extends AuthBaseController
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionList()
    {
        $model = new QuestionListForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($question = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($question);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCreate()
    {
        $model = new QuestionCreateForm(Yii::$app->user->id);
        $model->answer = Yii::$app->request->post();

        if (($question = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($question);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionFinish()
    {
        $model = new QuestionFinishForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($question = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($question);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionRedo()
    {
        $model = new QuestionRedoForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($question = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($question);
    }
}