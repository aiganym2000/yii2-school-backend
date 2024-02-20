<?php

use common\models\entity\Category;
use common\widgets\Panel;
use kartik\widgets\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'CATEGORIES');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

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
                'title',
                [
                    'attribute' => 'photo',
                    'value' => function (Category $model) {
                        return Html::img($model->getImgUrl(), ['width' => '120px']);
                    },
                    'format' => 'html',
                    'filter' => false,
                ],
//            'description:ntext',
//            'created_user',

                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function (Category $model) {
                        if ($model->status === $model::STATUS_ACTIVE) {
                            $class = 'label-success';
                        } else if ($model->status === $model::STATUS_NOT_ACTIVE) {
                            $class = 'label-warning';
                        } else {
                            $class = 'label-danger';
                        }

                        return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                    },
                    'filter' => Category::getStatusList()
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
