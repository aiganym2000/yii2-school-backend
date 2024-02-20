<?php

/* @var $model PromoForm */

use backend\modules\admin\models\PromoForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Modal::begin([
    'id' => 'modal-2',
    'header' => '<h4 class="center">Удалить по названию</h4>',
]);
?>
<?php $form = ActiveForm::begin(); ?>
    <div>
        <?= $form->field($model, 'promo')->textInput(['maxlength' => true]) ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'DELETE'), ['class' => 'btn btn-danger', 'data-confirm' => 'Вы уверены, что хотите удалить все активные промокоды с этим названием?']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>