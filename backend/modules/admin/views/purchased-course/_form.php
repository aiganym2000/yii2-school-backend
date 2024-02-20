<?php

use common\models\entity\Course;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\entity\PurchasedCourse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchased-course-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'course_id')->widget(Select2::classname(), [
                'data' => array_map(function ($v) {
                    return trim(strip_tags($v));
                }, ArrayHelper::map(Course::find()->all(), 'id', 'title')),
                'options' => ['placeholder' => 'Выберите курс'],
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
