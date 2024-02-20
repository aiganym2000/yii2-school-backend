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
            <?php $answer = json_decode($model->answer, true);
            $first = $answer['first'];
            $second = $answer['second'] ?>
            <?php foreach ($first as $a): ?>
                <div class="col-md-6 form-group"><input type="text" class="form-control" placeholder="Левая часть"
                                                        name="Answer[<?= $id ?>][first]" value="<?= $a['text'] ?>">
                </div>
                <div class="col-md-6 form-group"><input type="text" class="form-control" placeholder="Соотношение"
                                                        name="Answer[<?= $id ?>][second]"
                                                        value="<?= $second[$id]['text'] ?>"></div>
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
            '<div class="col-md-6 form-group"><input type="text" class="form-control" placeholder="Левая часть" name="Answer[' + x + '][first]" ></div>' +
            '<div class="col-md-6 form-group"><input type="text" class="form-control" placeholder="Соотношение" name="Answer[' + x + '][second]" > </div>' +
            '<div id="input' + (x + 1) + '">';
        document.getElementById('input' + x).innerHTML = str;
        x++;
    }
</script>

