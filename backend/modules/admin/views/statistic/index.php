<?php

use common\models\entity\Statistic;
use common\widgets\Panel;
use kartik\helpers\Html;
use kartik\widgets\DatePicker;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\StatisticSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statistic-index">

    <?php Panel::begin([
        'title' => Yii::t('app', 'STATISTICS')
    ]) ?>
    <div class="table-responsive">
        <?php Pjax::begin(['timeout' => 5000]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'date',
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date',
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
                'average_check',
                [
                    'attribute' => 'promocode_json',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function (Statistic $model) {
                        $text = '';
                        $promocodes = json_decode($model->promocode_json, true);
                        foreach ($promocodes as $key => $value) {
                            $text .= $key . ': ' . $value . ' раза<br>';
                        }

                        return $text;
                    }
                ],
                [
                    'attribute' => 'author_json',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function (Statistic $model) {
                        $text = '';
                        $authors = json_decode($model->author_json, true);
                        foreach ($authors as $key => $value) {
                            $text .= Html::a($value['fio'], ['/admin/author/view', 'id' => $key]) . ': ' . $value['count'] . ' раз, ' . $value['sum'] . ' рублей<br>';
                        }

                        return $text;
                    }
                ],
//            'created_at',
                //'updated_at',
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
    <?php Panel::end() ?>
</div>
