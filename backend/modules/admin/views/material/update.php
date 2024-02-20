<?php

/* @var $this yii\web\View */
/* @var $model common\models\Material */


$this->title = Yii::t('app', 'UPDATE_MATERIAL: {name}', ['name' => $model->title]);
$this->params['breadcrumbs'][] = ['label' => $this->title = Yii::t('app', 'MATERIALS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'UPDATE');
?>
<div class="news-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
