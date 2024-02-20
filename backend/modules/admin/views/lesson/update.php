<?php

use common\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Lesson */

$this->params['breadcrumbs'][] = ['label' => strip_tags($model->course->title), 'url' => ['/admin/course/view', 'id' => $model->course_id]];
$this->title = Yii::t('app', 'UPDATE_LESSON: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'UPDATE');
?>
<div class="lesson-update">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}',
        'buttons' => [
            'cancel' => [
                'url' => ['/admin/course/view', 'id' => $model->course_id],
                'icon' => 'fa fa-reply',
                'options' => ['class' => 'btn btn-sm btn-default btn btn-primary']
            ]
        ]
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
        'isNew' => false,
    ]) ?>

    <?php Panel::end() ?>

</div>
