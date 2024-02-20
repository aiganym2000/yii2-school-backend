<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\entity\User */

$params = Yii::$app->params;
$resetLink = $params['frontDomain'] . $params['passwordResetAddress'] . '/' . $user->password_reset_token;
?>
<p>Вы получили это письмо, потому что вы отправили запрос на восстановление доступа к своей учетной
    записи.</p>
<p>Для восстановления пароля перейдите, пожалуйста, по следующей
    ссылке: <?= Html::a(Html::encode($resetLink), $resetLink, ['style' => 'color:#d9d9d9;']) ?></p>
<br>
<p>Если Вы не хотите изменять свой пароль, либо не запрашивали изменение, игнорируйте данное письмо.</p>
