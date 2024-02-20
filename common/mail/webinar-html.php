<?php

use common\models\entity\Webinar;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $webinar Webinar */
?>
<div class="password-reset">

    <p>Ваш вебинар пройдет <?= $webinar->date ?> по следующей ссылке:</p>

    <p><?= Html::a($webinar->link) ?></p>
</div>
