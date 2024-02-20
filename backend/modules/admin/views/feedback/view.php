<?php

use common\widgets\Panel;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Feedback */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'FEEDBACK'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="feedback-view">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}',
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'email:email',
            'text:ntext',
            'created_at',
        ],
    ]) ?>

    <?php Panel::end() ?>

</div>
