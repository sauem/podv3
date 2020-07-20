<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\UserModel;
?>

<div class="orders-model-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <label>Thông tin khách hàng</label>
            <?= $form->field($model, 'customer_name')
                ->textInput(['placeholder' => 'Tên khách hàng, email, số điện thoại...'])
                ->label(false) ?>
        </div>
        <div class="col-md-3">
            <label>Ngày lập đơn</label>
            <?= $form->field($model, 'created_at')
                ->textInput(['class' => 'daterange form-control'])
                ->label(false) ?>
        </div>
        <div class="col-md-3">
            <label>Người lập đơn</label>
            <?= Html::dropDownList("user_id[]", null, UserModel::listSales(), [
                'prompt' => 'Chọn tài khoản',
                'multiple' => true,
                'class' => 'select2 form-control'
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Tìm kiếm', ['class' => 'btn btn-primary']) ?>
        <?= \common\helper\Component::reset() ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
