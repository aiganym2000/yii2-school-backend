<?php

use common\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Transaction */

$this->title = Yii::t('app', 'UPDATE_TRANSACTION: {name}', ['name' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'TRANSACTIONS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'UPDATE');
?>
<div class="transaction-update">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}'
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Panel::end() ?>

</div>
