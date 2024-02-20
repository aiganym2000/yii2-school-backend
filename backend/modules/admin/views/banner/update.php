<?php

use common\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Banner */

$this->title = Yii::t('app', 'UPDATE_BANNER: {name}', ['name' => $model->title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BANNERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'UPDATE');
?>
<div class="banner-update">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}'
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Panel::end() ?>

</div>
