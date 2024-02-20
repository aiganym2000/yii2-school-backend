<?php

use common\widgets\Panel;


/* @var $this yii\web\View */
/* @var $model common\models\entity\PurchasedCourse */
$this->title = Yii::t('app', 'CREATE_PURCHASED_COURSE');
$this->params['breadcrumbs'][] = ['label' => $model->user->fullname, 'url' => ['/admin/user/view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchased-course-create">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}',
        'buttons' => [
            'cancel' => [
                'url' => ['/admin/user/view', 'id' => $model->user_id],
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
