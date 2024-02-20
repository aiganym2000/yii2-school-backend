<?php


namespace api\models\forms;

use common\models\services\BannerService;
use yii\base\Model;

class BannerViewForm extends Model
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

        return BannerService::viewBanner($this->id);
    }
}