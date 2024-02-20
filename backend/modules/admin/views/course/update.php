<?php

use common\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Course */

$this->title = Yii::t('app', 'UPDATE_COURSE: {name}', [
    'name' => strip_tags($model->title),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'COURSES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => strip_tags($model->title), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'UPDATE');
?>
<div class="course-update">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}'
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Panel::end() ?>

</div>
