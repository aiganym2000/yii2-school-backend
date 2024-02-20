<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\CartAddForm;
use api\models\forms\CartCountForm;
use api\models\forms\CartDeleteAllForm;
use api\models\forms\CartDeleteForm;
use api\models\forms\CartListForm;
use api\models\forms\FullAccessForm;
use api\models\forms\PromoAddForm;
use api\models\forms\PromoCheckForm;
use api\models\forms\PromoDeleteForm;
use api\models\helper\RequestHelper;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;

class CartController extends AuthBaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'except' => [
                'promo-check',
            ],
        ];

        return $behaviors;
    }

    public function actionList()
    {
        $model = new CartListForm(Yii::$app->user->id);

        if (($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    public function actionAdd()
    {
        $model = new CartAddForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    public function actionDelete()
    {
        $model = new CartDeleteForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    public function actionCount()
    {
        $model = new CartCountForm(Yii::$app->user->id);

        if (($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    public function actionDeleteAll()
    {
        $model = new CartDeleteAllForm(Yii::$app->user->id);

        if (($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    public function actionPromoAdd()
    {
        $model = new PromoAddForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success();
    }

    public function actionPromoCheck()
    {
        $model = new PromoCheckForm();

        if (!$model->load(Yii::$app->request->post(), '') || ($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

    public function actionPromoDelete()
    {
        $model = new PromoDeleteForm(Yii::$app->user->id);

        if (($course = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($course);
    }

//    public function actionCheckout()
//    {
//        $model = new CheckoutForm(Yii::$app->user->id);
//
//        if (($course = $model->save()) === false)
//            RequestHelper::exceptionModel($model);
//
//        return RequestHelper::success($course);
//    }

    public function actionFullAccess()
    {
        $model = new FullAccessForm(Yii::$app->user->id);

        if (($user = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($user);
    }
}