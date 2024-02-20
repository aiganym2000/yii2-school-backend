<?php

namespace api\versions\v1\controllers;

use api\models\forms\CryptPostbackForm;
use api\models\forms\PostSecureForm;
use api\models\forms\StripeRetrieveForm;
use api\models\helper\RequestHelper;
use Yii;
use yii\rest\Controller;
use yii\web\ConflictHttpException;

class PostbackController extends Controller
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCryptPostback()
    {
        $model = new CryptPostbackForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($pay = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success();
    }

    public function actionCpPostSecure()
    {
        $model = new PostSecureForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($pay = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return $this->redirect($pay);
    }

    /**
     * @return \yii\web\Response
     * @throws ConflictHttpException
     */
    public function actionRetrieveStripe()
    {
        $model = new StripeRetrieveForm();

        if (!$model->load(Yii::$app->request->get(), '') || ($pay = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return $this->redirect($pay);
    }
}