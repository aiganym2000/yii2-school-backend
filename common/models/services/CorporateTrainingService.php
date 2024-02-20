<?php

namespace common\models\services;

use api\models\helper\ErrorMsgHelper;
use common\models\entity\CorporateTraining;
use Yii;
use yii\web\ConflictHttpException;

class CorporateTrainingService extends CorporateTraining
{
    public static function createCorporateTraining($name, $phone, $email, $text, $theme)
    {
        $training = new self();
        $training->name = $name;
        $training->phone = $phone;
        $training->email = $email;
        $training->status = self::STATUS_ACTIVE;
        $training->text = $text;
        $training->theme = $theme;

        if (!$training->save())
            throw new ConflictHttpException(ErrorMsgHelper::getErrorMsg($training));

        $params = Yii::$app->params;
        $res = Yii::$app->mailer->compose(['html' => 'training-html'])
            ->setFrom([$params['robotEmail'] => $params['robotName']])
            ->setTo($email)
            ->setSubject('Корпоративное обучение')
            ->send();

        $res = Yii::$app->mailer->compose(['html' => 'trainingInfo-html'], ['training' => $training])
            ->setFrom([$params['robotEmail'] => $params['robotName']])
            ->setTo($params['infoEmail'])
            ->setSubject('Корпоративное обучение')
            ->send();

        return $training;
    }
}