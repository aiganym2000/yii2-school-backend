<?php


namespace api\models\forms;

use common\models\services\CategoryService;
use yii\base\Model;

class CategoryListForm extends Model
{
    public function save()
    {
        if (!$this->validate())
            return false;

        return CategoryService::listCategory();
    }
}