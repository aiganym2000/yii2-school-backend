<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\StatisticPromocodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="statistic-promocode-index">

    <div class="table-responsive">
        <?php Pjax::begin(['timeout' => 5000]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'date',
                'promo',
                'count',
//                'created_at',
                //'updated_at',
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
