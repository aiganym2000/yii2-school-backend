<?php

use common\models\entity\Course;
use common\models\entity\PurchasedCourse;
use common\widgets\Panel;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\PurchasedCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $userId int */
?>
<div class="purchased-course-index">

    <?php Panel::begin([
        'buttonsTemplate' => '{create}',
        'buttons' => [
            'create' => [
                'url' => ['/admin/purchased-course/create', 'userId' => $userId],
                'icon' => 'fa fa-plus',
                'options' => ['class' => 'btn btn-primary']
            ]
        ]
    ]) ?>
    <div class="table-responsive">
        <?php Pjax::begin(['timeout' => 5000]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
//                [
//                    'attribute' => 'user_id',
//                    'format' => 'html',
//                    'value' => function (PurchasedCourse $model) {
//                        if ($model->user)
//                            return Html::a($model->user->id, ['/admin/user/view', 'id' => $model->user_id]);
//                    },
//                    'filter' => Select2::widget([
//                        'model' => $searchModel,
//                        'attribute' => 'user_id',
//                        'data' => ArrayHelper::map(User::find()->all(), 'id', 'id'),
//                        'value' => 'name',
//                        'options' => [
//                            'class' => 'form-control',
//                            'placeholder' => 'Выберите пользователя'
//                        ],
//                        'pluginOptions' => [
//                            'allowClear' => true,
//                            'selectOnClose' => true,
//                        ]
//                    ])
//                ],
                [
                    'attribute' => 'course_id',
                    'format' => 'html',
                    'value' => function (PurchasedCourse $model) {
                        return Html::a(strip_tags($model->course->title), ['/admin/course/view', 'id' => $model->course_id]);
                    },
                    'filter' => Select2::widget([
                        'attribute' => 'course_id',
                        'model' => $searchModel,
                        'data' => array_map(function ($v) {
                            return trim(strip_tags($v));
                        }, ArrayHelper::map(Course::find()->all(), 'id', 'title')),
                        'value' => 'name',
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Выберите курс'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'selectOnClose' => true,
                        ]
                    ])
                ],
//            'price',
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
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $customurl = Yii::$app->getUrlManager()->createUrl(['admin/purchased-course/delete', 'id' => $model->id]);
                            return Html::a('<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path></svg>', $customurl,
                                ['title' => Yii::t('app', 'UPDATE'), 'data-pjax' => '0', 'class' => 'btn btn-sm btn-default']);
                        },
                    ],],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
    <?php Panel::end() ?>
</div>
