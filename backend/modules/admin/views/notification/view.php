<?php

use common\models\entity\Notification;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Notification */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NOTIFICATIONS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="notification-view">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}',
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            [
                'attribute' => 'user_id',
                'value' => function (Notification $model) {
                    if ($model->user)
                        return Html::a($model->user->id, ['/admin/user/view', 'id' => $model->user_id]);
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function (Notification $model) {
                    if ($model->status === $model::STATUS_READED) {
                        $class = 'label-success';
                    } else if ($model->status === $model::STATUS_SEND) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }
                    return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                },
            ],
            'created_at',
        ],
    ]) ?>

    <?php Panel::end() ?>

</div>
