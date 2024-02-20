<?php

use common\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Webinar */

$this->title = Yii::t('app', 'CREATE_WEBINAR');
$this->params['breadcrumbs'][] = ['label' => strip_tags($model->course->title), 'url' => ['/admin/course/view', 'id' => $model->course_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="webinar-create">

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
    ]) ?>

    <?php Panel::end() ?>

</div>
