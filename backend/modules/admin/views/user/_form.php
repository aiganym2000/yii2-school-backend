<?php

use vova07\fileapi\Widget as FileAPI;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\entity\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'ava')->widget(FileAPI::className(), [
                'settings' => [
                    'url' => ['img-upload']
                ]
            ]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'password_new')->textInput(['maxlength' => true])//todo     ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList($model::getStatusList(), ['prompt' => '']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'role')->dropDownList($model::getRoleList(), ['prompt' => '']) ?>
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
