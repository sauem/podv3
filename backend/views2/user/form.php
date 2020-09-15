<?php

use common\helper\Component;
use kartik\form\ActiveForm;
use backend\models\AuthItem;
use yii\helpers\ArrayHelper;

?>
<?php $form = ActiveForm::begin([
    'id' => 'userActiveForm'
]) ?>

    <div class="row">
        <div class="form-group col-md-12">
            <?= $form->field($model, 'username') ?>
        </div>
        <div class="form-group col-md-12">
            <?= $form->field($model, 'email') ?>
        </div>
        <?php
        if ($model->isNewRecord) { ?>
            <div class="form-group col-12">
                <?= $form->field($model, 'password_hash') ?>
            </div>
        <?php } ?>
        <div class="form-group col-md-12">
            <?php
            $model->role = $model->userRole ? $model->userRole->item_name : null ?>
            <?= $form->field($model, 'role')->dropDownList(
                AuthItem::Roles(),
                ['prompt' => 'Chọn quyền quản trị'])->label('Quyền quản trị') ?>
        </div>
        <div class="form-group col-md-12">
            <?= $form->field($model, 'country')->dropDownList(
                ArrayHelper::map(Yii::$app->params['country'], 'code', 'name'),
                ['prompt' => 'Thị trường quản lý', 'class' => 'select2']) ?>
        </div>
        <div class="form-group col-md-12">
            <?= $form->field($model, 'phone_of_day')->textInput(["type" => 'number', 'placeholder' => 'Số điện thoại giới hạn gọi']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>