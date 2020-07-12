<?php
use backend\models\ContactsModel;
use kartik\form\ActiveForm;

?>
<?php $form = ActiveForm::begin() ?>
<div class="row">
    <div class="col-12">
        <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-bar-chart"></i> Thông tin khách hàng</h5>
    </div>
    <div class="col-md-12">
        <?= $form->field($model,'customer_name')->label('Tên khách hàng')?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model,'customer_phone')->label('Số điện thoại')?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model,'customer_email')->label('Email')?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model,'address')->label('Địa chỉ')?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model,'district')->label('Quận/Huyện')?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model,'city')->label('Thành phố')?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model,'zipcode')->label('Zipcode')?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model,'country')->label('Quốc gia')?>
    </div>
    <div class="col-12">
        <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-bar-chart"></i> Sản phẩm đặt mua</h5>
    </div>
</div>
<?php ActiveForm::end() ?>