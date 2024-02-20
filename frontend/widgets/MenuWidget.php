<?php

namespace frontend\widgets;

use common\models\entity\Menu;
use common\models\Slider;
use yii\base\Widget;
use common\models\WidgetSt;
use common\models\Category;

class MenuWidget extends Widget
{
    public $nameWidget = 'menu';

    public function run()
    {
        $menu = Menu::find()
            ->where(['status' => Menu::STATUS_ACTIVE])
            ->all();

        return $this->render('menu', [
            'menu' => $menu
        ]);

    }
}