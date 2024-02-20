<?php

use common\models\entity\Banner;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Banner */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BANNERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="banner-view">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}',
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'path',
                'value' => function (Banner $model) {
                    return Html::img($model->getImgUrl(), ['width' => '120px']);
                },
                'format' => 'html',
            ],
            'size',
            'url',
            'position',
            [
                'attribute' => 'zone',
                'value' => function (Banner $model) {
                    return $model->getZoneLabel();
                },
            ],
            [
                'attribute' => 'created_user_id',
                'format' => 'html',
                'value' => function (Banner $model) {
                    if ($model->createdUser)
                        return Html::a($model->createdUser->id, ['/admin/user/view', 'id' => $model->created_user_id]);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function (Banner $model) {
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
            'published_at',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php Panel::end() ?>

</div>
