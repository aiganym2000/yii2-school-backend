<?php

use common\widgets\Panel;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Achievement */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ACHIEVEMENTS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="achievement-view">

    <?php Panel::begin([
        'title' => $this->title,
    ]) ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'letter',
            'title',
            'description',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php Panel::end() ?>

</div>
