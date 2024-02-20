<?php

use common\models\entity\Author;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Author */

$this->title = $model->fio;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'AUTHORS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="author-view">

    <?php Panel::begin([
        'title' => $this->title,
    ]) ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fio',
            'description:html',
            [
                'attribute' => 'photo',
                'value' => function (Author $model) {
                    return Html::img($model->getImgUrl(), ['width' => '120px']);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'video',
                'value' => function (Author $model) {
                    return '<video width="400" height="300" controls>
                              <source src="' . $model->getVideoUrl() . '" type="video/mp4">
                            </video>';
                },
                'format' => 'raw',
            ],
            'vimeo',
            [
                'attribute' => 'created_user',
                'format' => 'html',
                'value' => function (Author $model) {
                    if ($model->createdUser)
                        return Html::a($model->createdUser->id, ['/admin/user/view', 'id' => $model->created_user]);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function (Author $model) {
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
        ],
    ]) ?>

    <?php Panel::end() ?>
</div>
