<?php

use common\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\entity\CorporateTraining */

$this->title = Yii::t('app', 'CREATE_CORPORATE_TRAINING');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'CORPORATE_TRAINING'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="corporate-training-create">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}'
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Panel::end() ?>

</div>
