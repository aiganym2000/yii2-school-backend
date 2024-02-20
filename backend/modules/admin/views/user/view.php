<?php

use backend\modules\admin\models\search\AchievementUserSearch;
use backend\modules\admin\models\search\PurchasedCourseSearch;
use common\models\entity\User;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model User */
/* @var $userSearchModel AchievementUserSearch */
/* @var $userDataProvider yii\data\ActiveDataProvider */
/* @var $courseSearchModel PurchasedCourseSearch */
/* @var $courseDataProvider yii\data\ActiveDataProvider */

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'USERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="user-view">

    <?php Panel::begin([
        'title' => $this->title,
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fullname',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            [
                'attribute' => 'role',
                'format' => 'html',
                'value' => function (User $model) {
                    return Html::tag('span', $model->getRoleLabel());
                },
            ],
            [
                'attribute' => 'ava',
                'value' => function (User $model) {
                    return Html::img($model->getImgUrl(), ['width' => '120px']);
                },
                'format' => 'html',
            ],
            'phone',
            [
                'attribute' => 'full_access',
                'value' => function (User $model) {
                    return $model->full_access ? 'Да' : 'Нет';
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function (User $model) {
                    if ($model->status === $model::STATUS_ACTIVE) {
                        $class = 'label-success';
                    } else if ($model->status === $model::STATUS_REGISTRATION) {
                        $class = 'label-primary';
                    } else if ($model->status === $model::STATUS_NOT_ACTIVE) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }
                    return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => ['DateTime', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['DateTime', 'php:Y-m-d H:i:s'],],
        ],
    ]) ?>

    <?php Panel::end() ?>

    <?php Panel::begin() ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#course" data-toggle="tab"><h4><?= Yii::t('app', 'PURCHASED_COURSES') ?></h4>
                </a></li>
            <li><a href="#achievement" data-toggle="tab"><h4><?= Yii::t('app', 'ACHIEVEMENT_USERS') ?></h4></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="course">
                <?= $this->render('/purchased-course/index', [
                    'searchModel' => $courseSearchModel,
                    'dataProvider' => $courseDataProvider,
                    'userId' => $model->id,
                ]); ?>
            </div>
            <div class="tab-pane" id="achievement">
                <?= $this->render('/achievement-user/index', [
                    'searchModel' => $userSearchModel,
                    'dataProvider' => $userDataProvider,
                    'achievementId' => $model->id,
                ]); ?>
            </div>
        </div>
    </div>

    <?php Panel::end() ?>

</div>

<script src="/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>