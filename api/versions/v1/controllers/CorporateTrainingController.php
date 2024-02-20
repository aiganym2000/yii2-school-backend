<?php

namespace api\versions\v1\controllers;

use api\models\forms\CorporateTrainingForm;
use api\models\helper\RequestHelper;
use Yii;
use yii\rest\Controller;
use yii\web\ConflictHttpException;

class CorporateTrainingController extends Controller
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCreate()
    {
        $model = new CorporateTrainingForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($training = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($training);
    }
}