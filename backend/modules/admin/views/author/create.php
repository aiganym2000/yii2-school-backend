<?php

use common\widgets\Panel;


/* @var $this yii\web\View */
/* @var $model common\models\entity\Author */
$this->title = Yii::t('app', 'CREATE_AUTHOR');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'AUTHORS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-create">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}'
    ]) ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Panel::end() ?>
</div>
