<?php

namespace api\versions\v1\controllers;

use api\controllers\AuthBaseController;
use api\models\forms\PurchasedWebinarAddForm;
use api\models\forms\PurchasedWebinarListForm;
use api\models\helper\RequestHelper;
use Yii;
use yii\web\ConflictHttpException;

class PurchasedWebinarController extends AuthBaseController
{
    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionList()
    {
        $model = new PurchasedWebinarListForm(Yii::$app->user->id);

        if (($purchased = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        return RequestHelper::success($purchased);
    }

    /**
     * @return array
     * @throws ConflictHttpException
     */
    public function actionAdd()
    {
        $model = new PurchasedWebinarAddForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || ($purchased = $model->save()) === false)
            RequestHelper::exceptionModel($model);

        $text = 'Вебинар ' . $purchased->webinar->title . ' пройдет ' . $purchased->webinar->date . '. Вы получите ссылку на электронный адрес в день вебинара.';
        return RequestHelper::success($text);
    }
}