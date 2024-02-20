<?php

use common\models\entity\Author;
use common\models\entity\Category;
use dosamigos\tinymce\TinyMce;
use kartik\widgets\Select2;
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Course */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="course-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'title')->widget(TinyMce::className(), [
                'options' => ['rows' => 3],
                'language' => 'ru',
                'clientOptions' => [
                    'rows' => 3,
                    'plugins' => [
                        "advlist autolink lists link charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste"
                    ],
                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                ]
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'photo')->widget(FileAPI::className(), [
                'settings' => [
                    'url' => ['img-upload']
                ]
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'price_photo')->widget(FileAPI::className(), [
                'settings' => [
                    'url' => ['img-upload']
                ]
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'trailer')->widget(FileAPI::className(), [
                'settings' => [
                    'url' => ['video-upload']
                ]
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'vimeo')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'apple_id')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'status')->dropDownList($model::getStatusList(), ['prompt' => '']) ?>
        </div>
        <div class="col-md-3"><?= $form->field($model, 'author_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Author::find()->all(), 'id', 'fio'),
                'options' => ['placeholder' => 'Выберите автора'],
            ]); ?></div>
        <div class="col-md-3"><?= $form->field($model, 'category_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Category::find()->all(), 'id', 'title'),
                'options' => ['placeholder' => 'Выберите категорию'],
            ]); ?></div>
        <div class="col-md-3">
            <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'short_description')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'description')->widget(TinyMce::className(), [
                'options' => ['rows' => 6],
                'language' => 'ru',
                'clientOptions' => [
                    'plugins' => [
                        "advlist autolink lists link charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste"
                    ],
                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                ]
            ]); ?>
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
