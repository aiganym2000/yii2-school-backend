<?php

use common\widgets\Panel;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\FeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'FEEDBACK');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-index">

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
                'name',
                'email:email',
//            'text:ntext',
                'created_at',

                ['class' => '\common\components\grid\ActionColumn',
                    'template' => '{view}'],
            ],
        ]); ?>
    </div>
    <?php Panel::end() ?>
</div>
