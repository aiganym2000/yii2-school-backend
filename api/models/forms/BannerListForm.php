<?php


namespace api\models\forms;

use common\models\services\BannerService;
use yii\base\Model;

class BannerListForm extends Model
{
    public function save()
    {
        if (!$this->validate())
            return false;

        return BannerService::listBanner();
    }
}