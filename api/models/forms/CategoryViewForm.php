<?php


namespace api\models\forms;

use common\models\services\CategoryService;
use yii\base\Model;

class CategoryViewForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return CategoryService::viewCategory($this->id);
    }
}