<?php
use backend\models\ContactsModel;
use kartik\form\ActiveForm;

?>
<div class="modal fade" id="takeNoteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <div class="form-group col-12">
                        <?= $form->field($model, 'status')->widget(\kartik\select2\Select2::className(),[
                            'data' => \backend\models\ContactsModel::STATUS,
                            'options' => ['prompt' => 'Chọn trạng thái cuộc gọi']
                        ])->label("Trạng thái hiện tại") ?>

                    </div>

                    <div class="form-group col-12">
                        <?= $form->field($model, 'note')->textarea()->label('Ghi chú') ?>
                        <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->getId()])->label(false) ?>
                        <?= $form->field($model, 'contact_id')->hiddenInput(['value' => 6])->label(false) ?>
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