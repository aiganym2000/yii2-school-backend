<?php

use common\models\entity\Promocode;
use common\models\entity\Transaction;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Promocode */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'PROMOCODES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="promocode-view">

    <?php Panel::begin([
        'title' => $this->title,
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'promo',
            'percent',
            [
                'label' => 'Транзакция',
                'format' => 'html',
                'value' => function (Promocode $model) {
                    $transaction = Transaction::findOne(['promocode_id' => $model->id]);
                    if ($transaction)
                        return Html::a($transaction->id, ['/admin/transaction/view', 'id' => $transaction->id]);
                    return '';
                },
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
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php Panel::end() ?>

</div>
