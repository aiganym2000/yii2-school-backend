<?php

use common\models\entity\Notification;
use common\models\entity\User;
use common\widgets\Panel;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'NOTIFICATIONS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">
    <?php Panel::begin([
        'title' => $this->title,
    ]) ?>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'title',
//            'description:ntext',
                [
                    'attribute' => 'user_id',
                    'value' => function (Notification $model) {
                        if ($model->user)
                            return Html::a($model->user->id, ['/admin/user/view', 'id' => $model->user_id]);
                    },
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'user_id',
                        'data' => ArrayHelper::map(User::find()->all(), 'id', 'id'),
                        'value' => 'name',
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Выберите пользователя'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'selectOnClose' => true,
                        ]
                    ])
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
                    'filter' => Notification::getStatusList()
                ],
                [
                    'attribute' => 'created_at',
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                        'options' => [
                            'autocomplete' => 'off'
                        ]
                    ]),
                ],

                ['class' => '\common\components\grid\ActionColumn',
                    'template' => '{view}'],
            ],
        ]); ?>
    </div>
    <?php Panel::end() ?>

</div>
