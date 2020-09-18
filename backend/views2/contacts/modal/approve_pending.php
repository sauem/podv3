<?php

use kartik\form\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'id' => 'editPendingContact'
]); ?>

<?= $form->field($model, 'name')->textInput(['required' => true])->label("Tên khách hàng (*)") ?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'phone')->textInput(['required' => true])->label("Số điện thoại (*)") ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'code')->textInput(['required' => true])->label("Mã contact (*)") ?>
    </div>
</div>
<?= $form->field($model, 'address')->textarea(['rows' => 3])->label("Địa chỉ") ?>
<?= $form->field($model, 'zipcode')->textInput() ?>
<?= $form->field($model, 'option')->textarea(['required' => true])->label("Yêu cầu") ?>
<?= $form->field($model, 'link')->widget(\kartik\select2\Select2::className(), [
    'data' => \backend\models\LandingPages::selectOption("link","link"),
    'options' => [
        'prompt' => 'Chọn trang đích!',
        'required' => true,
        'template' => \kartik\select2\Select2::THEME_DEFAULT
    ]
])->label("Link landing page (bắt buộc)") ?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'status')->widget(\kartik\select2\Select2::className(), [
            'data' => \backend\models\ContactsModel::STATUS
        ])->label("Trạng thái") ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'country')->widget(\kartik\select2\Select2::className(), [
            'data' => \yii\helpers\ArrayHelper::map(Yii::$app->params['country'], 'code', 'name')
        ])->label("Quốc gia") ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
    </div>
</div>


<?= $form->field($model, 'note')->textInput(['maxlength' => true])->label("Ghi chú của khách") ?>
<?= $form->field($model, 'utm_source')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'utm_medium')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'utm_content')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'utm_term')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'utm_campaign')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'host')->textInput(['maxlength' => true]) ?>


<?php ActiveForm::end(); ?>
