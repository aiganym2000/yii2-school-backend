<?php

/* @var $this yii\web\View */
/* @var $training CorporateTraining */

use common\models\entity\CorporateTraining;

$params = Yii::$app->params;
?>
<div class="password-reset">
    Пришла заявка на корпоративное обучение.<br>
    Имя: <?= $training->name ?><br>
    Тема: <?= $training->theme ?><br>
    Почта: <?= $training->email ?><br>
    Телефон: <?= $training->phone ?><br>
    Текст: <?= $training->text ?><br>
</div>
