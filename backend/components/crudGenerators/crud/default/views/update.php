<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$modelClassName = Inflector::camel2words(StringHelper::basename($generator->modelClass));
$nameAttributeTemplate = '$model->' . $generator->getNameAttribute();
//$titleTemplate = $generator->generateString('Update ' . $modelClassName . ': {name}', ['name' => '{nameAttribute}']);
//$this->title = Yii::t('main', 'UPDATE_MATERIAL: {name}', ['name' => $model->title]);

$titleTemplate = "Yii::t('main', ".$generator->generateString('UPDATE_' . mb_strtoupper($modelClassName) . ': {name}', ['name' => '{nameAttribute}']).")";
if ($generator->enableI18N) {
    $title = strtr($titleTemplate, ['\'{nameAttribute}\'' => $nameAttributeTemplate]);
} else {
    $title = strtr($titleTemplate, ['{nameAttribute}\'' => '\' . ' . $nameAttributeTemplate]);
}

echo "<?php\n";
?>

use yii\helpers\Html;
use common\widgets\Panel;



/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $title ?>;
$this->params['breadcrumbs'][] = ['label' =>  Yii::t('main', <?= mb_strtoupper($generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))))) ?>), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = Yii::t('main',     <?= $generator->generateString('UPDATE')?>);
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">

    <?php echo "<?php Panel::begin([
    'title' => \$this->title,
    'buttonsTemplate' => '{cancel}'
])?>"; ?>

    <?= '<?= ' ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php echo "<?php Panel::end() ?>"; ?>

</div>
