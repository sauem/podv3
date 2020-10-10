<?php

use backend\models\LandingPages;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

?>
<?php
    Pjax::begin();
?>
<?php $form = ActiveForm::begin([
    'id' => 'contactDetail',
]); ?>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'option')->textInput()->label('Yêu cầu đặt hàng') ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'link')->widget(Select2::className(), [
            'data' => LandingPages::selectOption('link', 'link'),

        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'country')->widget(Select2::className(), [
            'data' => ArrayHelper::map(Yii::$app->params['country'], 'code', 'name')
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'zipcode')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'register_time')->textInput() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php Pjax::end(); ?>

