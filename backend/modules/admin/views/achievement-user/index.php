<?php

use common\models\entity\Achievement;
use common\models\entity\AchievementUser;
use common\widgets\Panel;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\AchievementUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="achievement-user-index">

    <?php Panel::begin() ?>
    <div class="table-responsive">
        <?php Pjax::begin(['timeout' => 5000]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'achievement_id',
                    'format' => 'html',
                    'value' => function (AchievementUser $model) {
                        return Html::a($model->achievement->title, ['/admin/achievement/view', 'id' => $model->achievement_id]);
                    },
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'achievement_id',
                        'data' => ArrayHelper::map(Achievement::find()->all(), 'id', 'title'),
                        'value' => 'name',
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Выберите достижение'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'selectOnClose' => true,
                        ]
                    ])
                ],
//                [
//                    'attribute' => 'user_id',
//                    'format' => 'html',
//                    'value' => function (AchievementUser $model) {
//                        return Html::a($model->user->id, ['/admin/user/view', 'id' => $model->user_id]);
//                    },
//                    'filter' => Select2::widget([
//                        'model' => $searchModel,
//                        'attribute' => 'user_id',
//                        'data' => ArrayHelper::map(User::find()->all(), 'id', 'fullname'),
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
                'for',
                [
                    'attribute' => 'type',
                    'format' => 'html',
                    'value' => function (AchievementUser $model) {
                        return $model->getTypeLabel();
                    },
                    'filter' => AchievementUser::getTypeList()
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

                [
                    'class' => '\common\components\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            $customurl = Yii::$app->getUrlManager()->createUrl(['admin/achievement-user/view', 'id' => $model->id]);
                            return Html::a('<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"></path></svg>', $customurl,
                                ['title' => Yii::t('app', 'UPDATE'), 'data-pjax' => '0', 'class' => 'btn btn-sm btn-default']);
                        },
                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
    <?php Panel::end() ?>

</div>
