<?php

use common\widgets\Panel;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'MATERIALS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{create}'
    ]) ?>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => '\common\components\grid\SerialColumn'],

                'id',
                'title',
                'content:ntext',
                'img',
                'status',
                //'slug',
                //'meta_description',
                //'meta_keywords',
                //'created_at',
                //'updated_at',

                ['class' => '\common\components\grid\ActionColumn',
                    'template' => '{update}{view}{delete}'],
            ],
        ]); ?>
    </div>

    <?php Panel::end() ?>
</div>
