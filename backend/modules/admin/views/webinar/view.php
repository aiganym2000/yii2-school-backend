<?php

use common\models\entity\Webinar;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Webinar */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => strip_tags($model->course->title), 'url' => ['/admin/course/view', 'id' => $model->course_id]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="webinar-view">

    <?php Panel::begin([
        'title' => $this->title,
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'img',
                'value' => function (Webinar $model) {
                    return Html::img($model->getImgUrl(), ['width' => '120px']);
                },
                'format' => 'html',
            ],
            'link',
            'date',
            'price',
            'description',
            [
                'attribute' => 'course_id',
                'format' => 'html',
                'value' => function (Webinar $model) {
                    return Html::a(strip_tags($model->course->title), '/admin/course/view?id=' . $model->course->id);
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function (Webinar $model) {
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
            [
                'attribute' => 'created_user_id',
                'format' => 'html',
                'value' => function (Webinar $model) {
                    if ($model->createdUser)
                        return Html::a($model->createdUser->id, ['/admin/user/view', 'id' => $model->created_user_id]);
                }
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php Panel::end() ?>

</div>
