<?php

use common\models\entity\Lesson;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Lesson */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => strip_tags($model->course->title), 'url' => ['/admin/course/view', 'id' => $model->course_id]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="lesson-view">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}',
        'buttons' => [
            'cancel' => [
                'url' => ['/admin/course/view', 'id' => $model->course_id],
                'icon' => 'fa fa-reply',
                'options' => ['class' => 'btn btn-sm btn-default btn btn-primary']
            ]
        ],
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:html',
            [
                'attribute' => 'course_id',
                'format' => 'html',
                'value' => function (Lesson $model) {
                    return Html::a(strip_tags($model->course->title), ['/admin/course/view', 'id' => $model->course_id]);
                }
            ],
            [
                'attribute' => 'created_user_id',
                'format' => 'html',
                'value' => function (Lesson $model) {
                    if ($model->createdUser)
                        return Html::a($model->createdUser->id, ['/admin/user/view', 'id' => $model->created_user_id]);
                }
            ],
            [
                'attribute' => 'video',
                'value' => function (Lesson $model) {
                    return '<video width="400" height="300" controls>
                              <source src="' . $model->getVideoUrl() . '" type="video/mp4">
                            </video>';
                },
                'format' => 'raw',
            ],
            'vimeo',
            'position',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function (Lesson $model) {
                    if ($model->status === $model::STATUS_ACTIVE) {
                        $class = 'label-success';
                    } else if ($model->status === $model::STATUS_NOT_ACTIVE) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }
                    return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                },
            ],
            'created_at',
            'updated_at',
        ]
    ]) ?>

    <?php Panel::end() ?>

</div>
