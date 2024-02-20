<?php

use common\models\entity\Banner;
use common\widgets\Panel;
use himiklab\sortablegrid\SortableGridView;
use kartik\date\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'BANNERS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{create}'
    ]) ?>
    <div class="table-responsive">
        <?= SortableGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'sortableAction' => 'sort',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'title',
                [
                    'attribute' => 'path',
                    'value' => function (Banner $model) {
                        return Html::img($model->getImgUrl(), ['width' => '120px']);
                    },
                    'format' => 'html',
                    'filter' => false,
                ],
//            'size',
                //'position',
                [
                    'attribute' => 'zone',
                    'value' => function (Banner $model) {
                        return $model->getZoneLabel();
                    },
                    'filter' => Banner::getZoneList(),
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function (Banner $model) {
                        if ($model->status === $model::STATUS_ACTIVE) {
                            $class = 'label-success';
                        } else if ($model->status === $model::STATUS_NOT_ACTIVE) {
                            $class = 'label-warning';
                        } else {
                            $class = 'label-danger';
                        }

                        return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                    },
                    'filter' => Banner::getStatusList(),
                ],
//            'published_at',
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
                //'updated_at',
                //'url:url',

                [
                    'class' => '\common\components\grid\ActionColumn',
                    'template' => '{view}{update}{delete}'
                ],
            ],
        ]); ?>
    </div>
    <?php Panel::end() ?>

</div>
