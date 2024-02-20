<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Question */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'answer')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div id="input0"></div>
    <div class="form-group" onclick="addInput()"><i class="fa fa-plus"></i>Добавить атрибут</div>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'SAVE'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    var x = 0;

    function addInput() {
        var str =
            '<div class="row"><div class="col-md-6 form-group"><input type="text" class="form-control" placeholder="Левая часть" name=Answer[' + x + '][first]" ></div>' +
            '<div class="col-md-6 form-group"><input type="text" class="form-control" placeholder="Соотношение" name=Answer[' + x + '][second]" > </div></div>' +
            '<div id="input' + (x + 1) + '">';
        document.getElementById('input' + x).innerHTML = str;
        x++;
    }
</script>

