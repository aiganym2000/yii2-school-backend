<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\ApplePayForm;
use api\models\forms\CpCreateForm;
use api\models\forms\CpWidgetCreateForm;
use api\models\forms\CryptCreateForm;
use api\models\forms\FaCpCreateForm;
use api\models\forms\FaCryptCreateForm;
use api\models\forms\FaStripeCreateForm;
use api\models\forms\StripeCreateForm;
use api\models\forms\TypeListForm;
use api\models\helper\RequestHelper;
use Stripe\Exception\ApiErrorException;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\ConflictHttpException;

class PayController extends AuthBaseController
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCreateCrypt()
    {
        $model = new CryptCreateForm(Yii::$app->user->id);
        $model->load(Yii::$app->request->post(), '');

        if (($pay = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($pay);
    }

    public function actionCreateCp()
    {
        $model = new CpCreateForm(Yii::$app->user->id);
        $model->load(Yii::$app->request->post(), '');
        $pay = $model->save();

        return RequestHelper::success($pay);
    }

    public function actionCreateCpWidget()
    {
        $model = new CpWidgetCreateForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($pay = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($pay);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionTypeList()
    {
        $model = new TypeListForm();

        if (($pay = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($pay);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     * @throws ApiErrorException
     */
    public function actionCreateStripe()
    {
        $model = new StripeCreateForm(Yii::$app->user->id);

        if (($pay = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($pay);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCreateCryptFa()
    {
        $model = new FaCryptCreateForm(Yii::$app->user->id);
        $model->load(Yii::$app->request->post(), '');

        if (($pay = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($pay);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCreateCpFa()
    {
        $model = new FaCpCreateForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), ''))
            RequestHelper::exceptionModel($model);
        $pay = $model->save();

        return RequestHelper::success($pay);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionCreateStripeFa()
    {
        $model = new FaStripeCreateForm(Yii::$app->user->id);

        if (($pay = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($pay);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionApplePay()
    {
        $model = new ApplePayForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($pay = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($pay);
    }
}