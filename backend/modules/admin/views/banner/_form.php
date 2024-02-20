<?php

use kartik\datetime\DateTimePicker;
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'zone')->dropDownList($model::getZoneList(), ['prompt' => '']) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'status')->dropDownList($model::getStatusList(), ['prompt' => '']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'published_at')->widget(DateTimePicker::className(), [
                'name' => 'published_at',
                'options' => ['placeholder' => Yii::t('app', 'PICK_A_DATE...')],
                'convertFormat' => true,
                'pluginOptions' => [
                    'format' => 'yyyy-MM-dd HH:i:00',
                    'startDate' => date('Y-m-d'),
                    'todayHighlight' => true
                ]
            ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'url')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'path')->widget(FileAPI::className(), [
                'settings' => [
                    'url' => ['img-upload']
                ]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'created_at')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'updated_at')->textInput(['disabled' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'SAVE'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
