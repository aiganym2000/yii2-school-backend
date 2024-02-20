<?php

use common\models\entity\Author;
use common\widgets\Panel;
use kartik\widgets\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\AuthorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'AUTHORS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{create}'
    ]) ?>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'fio',
                [
                    'attribute' => 'photo',
                    'value' => function (Author $model) {
                        return Html::img($model->getImgUrl(), ['width' => '120px']);
                    },
                    'format' => 'html',
                    'filter' => false,
                ],
//            'created_user',
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function (Author $model) {
                        if ($model->status === $model::STATUS_ACTIVE) {
                            $class = 'label-success';
                        } else if ($model->status === $model::STATUS_NOT_ACTIVE) {
                            $class = 'label-warning';
                        } else {
                            $class = 'label-danger';
                        }

                        return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                    },
                    'filter' => Author::getStatusList()
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
                //'updated_at',

                ['class' => '\common\components\grid\ActionColumn',
                    'template' => '{update}{view}{delete}'],
            ],
        ]); ?>
    </div>
    <?php Panel::end() ?>
</div>
