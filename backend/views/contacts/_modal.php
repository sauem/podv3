<?php

use backend\models\ContactsModel;
use kartik\form\ActiveForm;

?>
    <div class="modal fade" id="takeNoteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <?php $form = ActiveForm::begin([
                'id' => 'noteForm',
                'action' => \yii\helpers\Url::toRoute(['contacts-log/create'])
            ]) ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm trạng thái cuộc gọi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Trạng thái hiện tại</label>
                            <?= $form->field($model, 'status')
                                ->widget(\kartik\select2\Select2::className(), [
                                    'data' => \backend\models\ContactsModel::STATUS,
                                    'options' => ['prompt' => 'Chọn trạng thái cuộc gọi', 'style' => 'with:100%!important'],
                                ])->label(false) ?>

                        </div>

                        <div class="col-12">
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
<?php
$js = <<<JS
    $(".btnNoteModal").click(function() {
        let _contactID = $(this).data('contact');
        let _status = $(this).data('status');
        alert(_status)
        $("#takeNoteModal").modal();
        $("#noteForm").attr("data-contact",_contactID);
        $("#noteForm").find("ContactsLog[status]").val(_status)
    });
    $(document).on("beforeSubmit","#noteForm", function(e) {
        e.preventDefault();
        let _url = $(this).attr("action");
        let _form = new FormData($(this)[0]);
        let _contact_id = $(this).attr("data-contact");
            _form.append("ContactsLog[contact_id]",_contact_id);
        $.ajax({    
            url : _url,
            data : _form,
            type : 'POST',
            cache : false,
            contentType: false,
            processData: false,
            success : function(res) {
                if(res.success){
                    window.location.reload();
                }else{
                    toastr.warning(res.msg)
                }
            }
        });
        
        return false;
    });
JS;
$this->registerJs($js);