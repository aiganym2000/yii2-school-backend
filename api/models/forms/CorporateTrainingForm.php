<?php


namespace api\models\forms;

use common\models\services\CorporateTrainingService;
use yii\base\Model;

class CorporateTrainingForm extends Model
{
    public $name;
    public $text;
    public $phone;
    public $email;
    public $theme;

    public function rules()
    {
        return [
            [['name', 'text', 'email'], 'required'],
            [['name', 'text', 'phone', 'theme'], 'string'],
            [['email'], 'email'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return CorporateTrainingService::createCorporateTraining($this->name, $this->phone, $this->email, $this->text, $this->theme);
    }
}