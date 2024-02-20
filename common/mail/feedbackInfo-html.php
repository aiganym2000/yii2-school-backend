<?php

/* @var $this yii\web\View */
/* @var $feedback Feedback */

use common\models\entity\Feedback;

$params = Yii::$app->params;
?>
<div class="password-reset">
    Пришла обратная связь.<br>
    Имя: <?= $feedback->name ?><br>
    Почта: <?= $feedback->email ?><br>
    Текст: <?= $feedback->text ?><br>
</div>
