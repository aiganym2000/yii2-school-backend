<?php


namespace api\models\forms;


use Yii;
use yii\base\Model;

class CheckUserForm extends Model
{
    public $iin;

    public function rules()
    {
        return [
            [['iin'], 'required'],
            [['iin'], 'string', 'min' => 2, 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'iin' => 'ИИН',
        ];
    }
}