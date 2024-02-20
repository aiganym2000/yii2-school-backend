<?php

use common\models\entity\User;
use common\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model User */

$this->title = Yii::t('app', 'CREATE_USER');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'USERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}'
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Panel::end() ?>

</div>
