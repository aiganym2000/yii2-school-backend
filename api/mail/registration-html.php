<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $code string */
/* @var $email string */

$params = Yii::$app->params;
$resetLink = $params['frontDomain'] . $params['registrationAddress'] . '/' . $email ."/".$code;
?>
<div class="password-reset">

    <p>Нажмите на ссылку для активации аккаунта:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
