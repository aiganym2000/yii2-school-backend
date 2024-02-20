<?php

use backend\modules\admin\models\search\UserSearch;
use common\models\entity\User;
use common\widgets\Panel;
use kartik\widgets\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'USERS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{excel}{create}',
        'buttons' => [
            'excel' => [
                'url' => ['/admin/user/excel', 'start' => isset(Yii::$app->request->get('UserSearch')['from_date']) ? Yii::$app->request->get('UserSearch')['from_date'] : null, 'end' => isset(Yii::$app->request->get('UserSearch')['to_date']) ? Yii::$app->request->get('UserSearch')['to_date'] : null],
                'icon' => 'fa fa-download',
                'label' => 'Экспорт',
                'options' => ['class' => 'btn btn-success']
            ],
        ]
    ]) ?>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'fullname',
                [
                    'attribute' => 'ava',
                    'value' => function (User $model) {
                        return Html::img($model->getImgUrl(), ['width' => '120px']);
                    },
                    'format' => 'html',
                    'filter' => false,
                ],
//            'auth_key',
//            'password_hash',
                //'password_reset_token',
                'email:email',
                //'role',
                //'city_id',
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function (User $model) {
                        if ($model->status === $model::STATUS_ACTIVE) {
                            $class = 'label-success';
                        } else if ($model->status === $model::STATUS_NOT_ACTIVE) {
                            $class = 'label-warning';
                        } else {
                            $class = 'label-danger';
                        }

                        return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                    },
                    'filter' => User::getStatusList()
                ],
                [
                    'attribute' => 'role',
                    'format' => 'html',
                    'value' => function (User $model) {
                        return Html::tag('span', $model->getRoleLabel());
                    },
                    'filter' => User::getRoleList()
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['DateTime', 'php:Y-m-d H:i:s'],
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'from_date',
                        'attribute2' => 'to_date',
                        'type' => DatePicker::TYPE_RANGE,
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true,
                        ],
                        'options' => [
                            'autocomplete' => 'off'
                        ],
                        'options2' => [
                            'autocomplete' => 'off'
                        ]
                    ]),
                ],
                //'updated_at',

                ['class' => '\common\components\grid\ActionColumn',
                    'template' => '{view}{update}{delete}'],
            ],
        ]); ?>
    </div>
    <?php Panel::end() ?>
</div>
