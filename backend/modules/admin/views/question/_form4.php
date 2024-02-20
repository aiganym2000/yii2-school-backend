<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Question */
/* @var $form yii\widgets\ActiveForm */
$id = 0;
?>

<div class="question-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
        </div>

        <?php if ($model->answer): ?>
            <?php $answer = json_decode($model->answer, true); ?>
            <?php foreach ($answer as $a): ?>
                <div class="col-md-12 form-group">
                    <label for="<?= $id ?>">
                        <input type="text" class="form-control" placeholder="Текст ответа"
                               name=Answer[<?= $id ?>][text]" value="<?= $a['text'] ?>">
                    </label>
                    <input type="checkbox" id="<?= $id ?>" name="checkbox[]"
                           value="<?= $id ?>" <?php if ($a['right']) echo 'checked' ?>>
                </div>
                <?php $id++; endforeach; ?>
            <div id="input<?= $id ?>"></div>
        <?php else: ?>
            <div id="input0"></div>
        <?php endif; ?>
        <div class="col-md-12">
            <div class="form-group btn btn-primary" onclick="addInput()"><i class="fa fa-plus"></i>Добавить пункт</div>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'created_at')->textInput(['disabled' => true, 'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'updated_at')->textInput(['disabled' => true, 'maxlength' => true]) ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'SAVE'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    var x = <?= $id ?>;

    function addInput() {
        var str =
            '<div class="col-md-12 form-group"><label for="' + x + '"><input type="text" class="form-control" placeholder="Текст ответа" name=Answer[' + x + '][text]" ></label><input type="checkbox" id="' + x + '" name="checkbox[]" value="' + x + '"></div>' +
            '<div id="input' + (x + 1) + '">';
        document.getElementById('input' + x).innerHTML = str;
        x++;
    }
</script>

