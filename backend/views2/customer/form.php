<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\helper\Component;
?>

<div class="customers-form">

    <?php $form = ActiveForm::begin(); ?>

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
        <div class="col-md-6">
            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'district')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'country')->widget(Select2::className(), [
                'data' => ArrayHelper::map(Yii::$app->params['country'], 'code', 'name'),
                'theme' => Select2::THEME_DEFAULT,
                'options' => [
                    'prompt' => 'Quốc gia'
                ]
            ]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        </div>

    </div>

    <div class="form-group">
        <small class="text-danger"> * các trường bắt buộc</small>

        <?= Component::reset('Hủy')?>
        <?= Html::submitButton('<i class="fe-save"></i> Lưu', ['class' => 'btn-sm btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
