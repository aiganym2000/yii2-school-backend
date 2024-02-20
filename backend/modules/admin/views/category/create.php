<?php

use common\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Category */

$this->title = Yii::t('app', 'CREATE_CATEGORY');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'CATEGORIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}'
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Panel::end() ?>

</div>
