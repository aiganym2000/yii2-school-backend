<?php

namespace common\models\services;

use api\models\helper\ErrorMsgHelper;
use common\models\entity\Feedback;
use Yii;
use yii\web\ConflictHttpException;

class FeedbackService extends Feedback
{
    public static function createFeedback($name, $email, $text)
    {
        $feedback = new self();
        $feedback->name = $name;
        $feedback->email = $email;
        $feedback->text = $text;

        if (!$feedback->save())
            throw new ConflictHttpException(ErrorMsgHelper::getErrorMsg($feedback));

        $params = Yii::$app->params;
        $res = Yii::$app->mailer->compose(['html' => 'feedback-html'])
            ->setFrom([$params['robotEmail'] => $params['robotName']])
            ->setTo($email)
            ->setSubject('Обратная связь')
            ->send();
        $res = Yii::$app->mailer->compose(['html' => 'feedbackInfo-html'], ['feedback' => $feedback])
            ->setFrom([$params['robotEmail'] => $params['robotName']])
            ->setTo($params['infoEmail'])
            ->setSubject('Обратная связь')
            ->send();

        return $feedback;
    }
}