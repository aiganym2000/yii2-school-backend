<?php

use backend\modules\admin\models\PromoForm;
use common\models\entity\Promocode;
use common\models\entity\Transaction;
use common\widgets\Panel;
use kartik\widgets\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $promocodeModel PromoForm */
/* @var $searchModel backend\modules\admin\models\search\PromocodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'PROMOCODES');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promocode-index">

    <?= $this->render('delete_modal', ['model' => $promocodeModel]); ?>
    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{delete}{excel}{create}',
        'buttons' => [
            'excel' => [
                'url' => ['/admin/promocode/excel', 'start' => Yii::$app->request->get('PromocodeSearch')['from_date'], 'end' => Yii::$app->request->get('PromocodeSearch')['to_date']],
                'icon' => 'fa fa-download',
                'label' => 'Аналитика',
                'options' => ['class' => 'btn btn-success']
            ],
            'delete' => [
                'icon' => 'fa fa-trash',
                'label' => 'Удалить по названию',
                'options' => ['class' => 'btn btn-danger', 'data-toggle' => 'modal', 'data-target' => '#modal-2']
            ]
        ]
    ]) ?>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'promo',
                'percent',
                [
                    'label' => 'Транзакция',
                    'format' => 'html',
                    'value' => function (Promocode $model) {
                        if ($model->status == Promocode::STATUS_INACTIVE) {
                            $transaction = Transaction::findOne(['promocode_id' => $model->id, 'status' => Transaction::STATUS_PAID]);
                            if ($transaction)
                                return Html::a($transaction->id, ['/admin/transaction/view', 'id' => $transaction->id]);
                        }
                        return '';
                    },
                    'filter' => false,
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function (Promocode $model) {
                        if ($model->status === $model::STATUS_ACTIVE) {
                            $class = 'label-success';
                        } else if ($model->status === $model::STATUS_INACTIVE) {
                            $class = 'label-warning';
                        } else {
                            $class = 'label-danger';
                        }

                        return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                    },
                    'filter' => Promocode::getStatusList()
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['DateTime', 'php:Y-m-d H:i:s'],
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

                ['class' => '\common\components\grid\ActionColumn',
                    'template' => '{view}{delete}'],
            ],
        ]); ?>
    </div>
    <?php Panel::end() ?>
</div>
