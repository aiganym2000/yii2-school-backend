<?php

use common\widgets\Panel;


/* @var $this yii\web\View */
/* @var $model common\models\entity\Author */

$this->title = Yii::t('app', 'UPDATE_AUTHOR: {name}', ['name' => $model->fio]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'AUTHORS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fio, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'UPDATE');
?>
<div class="author-update">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}'
    ]) ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Panel::end() ?>
</div>
