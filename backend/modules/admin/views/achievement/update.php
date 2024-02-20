<?php

use common\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Achievement */

$this->title = Yii::t('app', 'UPDATE_ACHIEVEMENT: {name}', [
    'name' => $model->title . ', ' . $model->letter,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ACHIEVEMENTS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'UPDATE');
?>
<div class="achievement-update">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}'
    ]) ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Panel::end() ?>

</div>
