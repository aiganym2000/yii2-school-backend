<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $code string */
/* @var $email string */

$params = Yii::$app->params;
$resetLink = $params['frontDomain'] . $params['registrationAddress'] . '/' . $email . "/" . $code;
?>
<p>Для того, чтобы активировать свой аккаунт, перейдите по
    ссылке: <?= Html::a(Html::encode($resetLink), $resetLink, ['style' => 'color:#d9d9d9;']) ?></p>
