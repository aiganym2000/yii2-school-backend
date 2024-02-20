<?php

use common\widgets\Panel;
use kartik\widgets\DatePicker;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\AchievementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'ACHIEVEMENTS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="achievement-index">

    <?php Panel::begin([
        'title' => $this->title,
    ]) ?>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'letter',
                'title',
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
                    'template' => '{update}{view}'],
            ],
        ]); ?>
    </div>
    <?php Panel::end() ?>

</div>
