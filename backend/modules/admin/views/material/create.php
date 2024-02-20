<?php

use common\widgets\Panel;


/* @var $this yii\web\View */
/* @var $model common\models\Material */

$this->title = Yii::t('app', 'CREATE_MATERIAL');
$this->params['breadcrumbs'][] = ['label' => $this->title = Yii::t('app', 'MATERIALS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}'
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Panel::end() ?>

</div>
