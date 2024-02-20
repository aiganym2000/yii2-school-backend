<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use common\widgets\Panel;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
$this->title = Yii::t('main', <?= mb_strtoupper($generator->generateString('Create_' . Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>);
$this->params['breadcrumbs'][] = ['label' => Yii::t('main', <?= mb_strtoupper($generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))))) ?>), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">

    <?php echo "<?php Panel::begin([
    'title' => \$this->title,
    'buttonsTemplate' => '{cancel}'
])?>"; ?>

    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php echo "<?php Panel::end() ?>"; ?>

</div>
