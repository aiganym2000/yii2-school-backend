<?php

use common\models\entity\Transaction;
use common\models\entity\User;
use common\widgets\Panel;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'TRANSACTIONS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

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
                'amount',
//            'pay_id',
                [
                    'format' => 'html',
                    'attribute' => 'user_id',
                    'value' => function (Transaction $model) {
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
                    'attribute' => 'payment_type',
                    'format' => 'html',
                    'value' => function (Transaction $model) {
                        return Html::tag('span', $model->getTypeLabel());
                    },
                    'filter' => Transaction::getTypeList()
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function (Transaction $model) {
                        $class = '';
                        if ($model->status === $model::STATUS_PAID) {
                            $class = 'label-success';
                        } else if ($model->status === $model::STATUS_IN_WAITING) {
                            $class = 'label-warning';
                        } else if ($model->status === $model::STATUS_CANCELLED) {
                            $class = 'label-danger';
                        } else if ($model->status === $model::STATUS_CREATED) {
                            $class = 'label-primary';
                        }

                        return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                    },
                    'filter' => Transaction::getStatusList()
                ],
                [
                    'attribute' => 'created_at',
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'from_date',
                        'attribute2' => 'to_date',
                        'type' => DatePicker::TYPE_RANGE,
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true,
                        ],
                        'options' => [
                            'autocomplete' => 'off'
                        ],
                        'options2' => [
                            'autocomplete' => 'off'
                        ]
                    ]),
                ],
                //'updated_at',

                [
                    'class' => '\common\components\grid\ActionColumn',
                    'template' => '{view}'
                ],
            ],
        ]); ?>
    </div>
    <?php Panel::end() ?>

</div>
