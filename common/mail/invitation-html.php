<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $code string */

$params = Yii::$app->params;
$resetLink = $params['frontDomain'];
?>
<div class="password-reset">

    <p>Приглашаем вас на данный интернет-ресурс:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
