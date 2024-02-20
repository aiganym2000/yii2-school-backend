<?php


namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\entity\User;
use common\models\services\ReportService;
use common\models\services\UserService;
use Yii;
use yii\base\Model;

class InvitationForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            RequestHelper::exceptionModel($this);

        $params = Yii::$app->params;
        $res = Yii::$app->mailer->compose(['html' => 'invitation-html'], ['email' => $this->email])
            ->setFrom([$params['robotEmail'] => $params['robotName']])
            ->setTo($this->email)
            ->setSubject('Приглашение')
            ->send();

        if (!$res)
            RequestHelper::exception("Сообщение не отправлено");

        return true;
    }
}