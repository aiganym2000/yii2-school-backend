<?php

use common\models\entity\Author;
use common\models\entity\Category;
use common\models\entity\Course;
use common\widgets\Panel;
use himiklab\sortablegrid\SortableGridView;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'COURSES');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-index">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{create}'
    ]) ?>
    <div class="table-responsive">
        <?= SortableGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'sortableAction' => 'sortItem',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'title:html',
                [
                    'attribute' => 'photo',
                    'value' => function (Course $model) {
                        return Html::img($model->getImgUrl(), ['width' => '120px']);
                    },
                    'format' => 'html',
                    'filter' => false,
                ],
//                'description:ntext',
                [
                    'attribute' => 'author_id',
                    'format' => 'html',
                    'value' => function (Course $model) {
                        return Html::a($model->author->fio, ['/admin/author/view', 'id' => $model->author_id]);
                    },
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'author_id',
                        'data' => ArrayHelper::map(Author::find()->all(), 'id', 'fio'),
                        'value' => 'name',
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Выберите автора'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'selectOnClose' => true,
                        ]
                    ])
                ],
                [
                    'attribute' => 'category_id',
                    'format' => 'html',
                    'value' => function (Course $model) {
                        return Html::a($model->category->title, ['/admin/category/view', 'id' => $model->category_id]);
                    },
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'category_id',
                        'data' => ArrayHelper::map(Category::find()->all(), 'id', 'title'),
                        'value' => 'name',
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Выберите категорию'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'selectOnClose' => true,
                        ]
                    ])
                ],
                //'created_user',
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function (Course $model) {
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
                    'template' => '{update}{view}'],//{delete}
            ],
        ]); ?>
    </div>
    <?php Panel::end() ?>

</div>
