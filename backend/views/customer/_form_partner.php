<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\helper\Component;
use backend\models\LandingPages;

?>

<div class="customers-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="form-group col-md-12">
            <?= $form->field($model, 'username') ?>
        </div>
        <div class="form-group col-md-12">
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model,'is_partner')->hiddenInput(['value' => 1])->label(false)?>
            <?= $form->field($model,'role')->hiddenInput(['value' => 'Partner'])->label(false)?>
            <?= $form->field($model,'phone_of_day')->hiddenInput(['value' => 0])->label(false)?>
        </div>
        <?php
        if ($model->isNewRecord) { ?>
            <div class="form-group col-12">
                <?= $form->field($model, 'password_hash') ?>
            </div>
        <?php } ?>
        <div class="form-group col-12">
            <?= $form->field($model, 'page_id')->widget(Select2::className(), [
                'data' => LandingPages::selectOption(),
                'theme' => Select2::THEME_DEFAULT,
                'options' => [
                    'prompt' => 'Chọn landing page'
                ]
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= \common\helper\Component::reset('Hủy') ?>
        <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
        <small class="text-danger"> * các trường bắt buộc</small>
    </div>

    <?php ActiveForm::end(); ?>

</div>
