<?php


namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\entity\User;
use common\models\services\ReportService;
use common\models\services\UserService;
use Yii;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $fullname;
    public $password;
    public $email;
    public $phone;
    public $code;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->code = User::generateCode();
//        $this->code = "1234";
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'email'], 'required'],
            [['fullname'], 'string'],
            [['phone'], 'number'],
            [['password'], 'string', 'min' => 8],
            [['email'], 'email'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            RequestHelper::exceptionModel($this);

        $code = $this->code;

        $unique = UserService::getUniqueUser($this->email, $this->phone);

        $report = ReportService::createReport($this->email, $this->code);

        $params = Yii::$app->params;
        try {
            $res = Yii::$app->mailer->compose(['html' => 'registration-html'], ['code' => $this->code, 'email' => $this->email])
                ->setFrom([$params['robotEmail'] => $params['robotName']])
                ->setTo($this->email)
                ->setSubject('Регистрация')
                ->send();
        } catch (\Exception $e) {
            return RequestHelper::exception('Ошибка почты');
        }

        if (!$res)
            RequestHelper::exception("Сообщение не отправлено");

        $user = UserService::createUser($this->fullname, $this->email, $this->phone, $this->password);

        return true;
    }
}