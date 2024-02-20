<?php


namespace api\models\forms;

use common\models\services\FeedbackService;
use yii\base\Model;

class FeedbackForm extends Model
{
    public $name;
    public $text;
    public $email;

    public function rules()
    {
        return [
            [['name', 'text', 'email'], 'required'],
            [['name', 'text'], 'string'],
            [['email'], 'email'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return FeedbackService::createFeedback($this->name, $this->email, $this->text);
    }
}