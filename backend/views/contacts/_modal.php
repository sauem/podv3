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
                <div class="modal-body" id="resultNote">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>

<script id="note-template" type="text/x-handlebars-template">
    <div class="row">
        <div class="col-12">
           <div class="form-group">
               <label>Trạng thái hiện tại</label>
               <select name="ContactsLog[status]" class="form-control select2">
                   {{#each this.status}}
                   <option {{selected @key ../selected}} value="{{@key}}">{{this}}</option>
                   {{/each}}
               </select>
               <input type="hidden" name="ContactsLog[user_id]" value="<?=Yii::$app->user->getId()?>">
           </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label>Ghi chú</label>
                <textarea class="form-control" name="ContactsLog[note]"></textarea>
            </div>
        </div>
        <div class="col-12">
            <hr>
            <h5>Lịch sử liên hệ</h5>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td>Ngày gọi</td>
                        <td>Trạng thái</td>
                        <td>Ghi chú</td>
                    </tr>
                </thead>
                <tbody>
                    {{#each this.logs}}
                        <tr>
                            <td>{{created_at}}</td>
                            <td>{{status}}</td>
                            <td>{{note}}</td>
                        </tr>
                    {{/each}}
                </tbody>
            </table>
        </div>
    </div>
</script>
<?php
$noteLogs = \yii\helpers\Url::toRoute(['contacts-log/index']);
$js = <<<JS
    $(".btnNoteModal").click(function() {
        let _contactID = $(this).data('contact');
     
        $("#takeNoteModal").modal();
        $("#noteForm").attr("data-contact",_contactID);
        
        $.ajax({
            type: 'POST',
            data : {cid : _contactID},
            url : "$noteLogs",
            success : function(res) {
                if(res.success){
                    $("#resultNote").html(compileTemplate("note-template",res))
                }
            }
        })
    
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