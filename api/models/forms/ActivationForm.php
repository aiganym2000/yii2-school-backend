<?php

namespace api\models\forms;


use api\models\helper\ErrorMsgHelper;
use common\models\entity\Report;
use common\models\entity\User;
use common\models\services\ReportService;
use Yii;
use yii\base\Model;
use yii\web\ConflictHttpException;

/**
 * Class ActivationForm
 * @package api\models\forms
 */
class ActivationForm extends Model
{
    /**
     * @var
     */
    public $email;
    /**
     * @var
     */
    public $activationCode;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'activationCode'], 'required'],
            [['email', 'activationCode'], 'string'],
        ];
    }

    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     * @throws ConflictHttpException
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        $report = ReportService::getActiveRequest($this->email);

        if (!Yii::$app->security->validatePassword($this->activationCode, $report->code))
            throw new ConflictHttpException('Неверный код');

        $report->status = Report::STATUS_CONFIRMED;
        if (!$report->save())
            throw new ConflictHttpException('Не сохранен смс');

        $user = User::findOne(['email' => $this->email]);
        if (!$user)
            throw new ConflictHttpException('Пользователь не найден');

        $user->status = User::STATUS_ACTIVE;
        if (!$user->save())
            throw new ConflictHttpException(ErrorMsgHelper::getErrorMsg($user));

        return true;
    }
}