<?php

use kartik\form\ActiveForm;

?>
<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?php $form = ActiveForm::begin() ?>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tạo tài khoản mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <?= $form->field($model, 'username') ?>
                    </div>
                    <div class="form-group col-md-6">
                        <?= $form->field($model, 'email') ?>
                    </div>
                    <div class="form-group col-md-6">
                        <?= $form->field($model, 'password_hash') ?>
                    </div>
                    <div class="form-group col-md-6">
                        <?= $form->field($model, 'role')->widget(\kartik\select2\Select2::className(), [
                            'data' => \backend\models\AuthItem::Roles(),
                            'options' => ['prompt' => 'Chọn quyền quản trị']
                        ])->label('Quyền quản trị') ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>