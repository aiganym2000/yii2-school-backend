<?php


namespace api\models\forms;

use common\models\services\CategoryService;
use yii\base\Model;

class CategoryUpdateForm extends Model
{
    public $id;
    public $title;
    public $description;
    public $status;
    public $photo;

    public function rules()
    {
        return [
            [['status', 'title', 'description'], 'string'],
            [['id'], 'integer'],
            [['id'], 'required'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return CategoryService::updateCategory($this->id, $this->title, $this->description, $this->status, $this->photo);
    }
}