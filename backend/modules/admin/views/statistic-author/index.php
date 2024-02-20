<?php

use common\models\entity\StatisticAuthor;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\StatisticAuthorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="statistic-author-index">

    <div class="table-responsive">
        <?php Pjax::begin(['timeout' => 5000]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'date',
                [
                    'attribute' => 'author_id',
                    'format' => 'html',
                    'value' => function (StatisticAuthor $model) {
                        if ($model->author)
                            return Html::a($model->author->fio, ['/admin/author/view', 'id' => $model->author_id]);
                    },
                ],
                'count',
//                'created_at',
                //'updated_at',
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
