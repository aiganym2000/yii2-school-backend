<?php

use common\models\entity\AchievementUser;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\AchievementUser */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => $model->user->id, 'url' => ['/admin/user/view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="achievement-user-view">


    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}',
        'buttons' => [
            'cancel' => [
                'url' => ['/admin/user/view', 'id' => $model->use_id],
                'icon' => 'fa fa-reply',
                'options' => ['class' => 'btn btn-sm btn-default btn btn-primary']
            ]
        ]
    ]) ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'achievement_id',
                'format' => 'html',
                'value' => function (AchievementUser $model) {
                    return Html::a($model->achievement->title, ['/admin/achievement/view', 'id' => $model->achievement_id]);
                },
            ],
//                [
//                    'attribute' => 'user_id',
//                    'format' => 'html',
//                    'value' => function (AchievementUser $model) {
//                        return Html::a($model->user->id, ['/admin/user/view', 'id' => $model->user_id]);
//                    },
//                ],
            'for',
            [
                'attribute' => 'type',
                'format' => 'html',
                'value' => function (AchievementUser $model) {
                    return $model->getTypeLabel();
                },
            ],
            'created_at',
        ],
    ]) ?>

</div>
