<?php


namespace api\models\forms;

use common\models\services\AuthorService;
use yii\base\Model;

class AuthorUpdateForm extends Model
{
    public $id;
    public $fio;
    public $description;
    public $photo;
    public $status;

    public function rules()
    {
        return [
            [['status', 'fio', 'description'], 'string'],
            [['id'], 'integer'],
            [['id'], 'required'],
            ['status', 'in', 'range' => ['ACTIVE', 'INACTIVE']],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        return AuthorService::updateAuthor($this->id, $this->fio, $this->status, $this->description, $this->photo);
    }
}