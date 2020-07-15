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
                    <select name="status" class="form-control select2">
                        {{#each this.status}}
                        {{#unless  (isNull @key)}}
                        <option {{selected @key ..
                        /selected}} value="{{@key}}">{{this}}
                        </option>
                        {{/unless }}
                        {{/each}}
                    </select>
                    <input type="hidden" name="user_id" value="<?= Yii::$app->user->getId() ?>">
                    <input type="hidden" name="phone" value="<?= Yii::$app->request->get('phone') ?>">
                </div>
                <div class="form-group callback-group" style="display: none">
                    <label>Goị lại sau (giờ):</label>
                    <input class="form-control" type="number" name="callback_time" placeholder="Gọi lại sau 3 giờ">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label>Ghi chú</label>
                    <textarea class="form-control" name="note"></textarea>
                </div>
            </div>
            {{#if this.logs}}
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
                        <td># {{date created_at}}</td>
                        <td>{{span status ../status}}</td>
                        <td>{{note}}</td>
                    </tr>
                    {{/each}}
                    </tbody>
                </table>
            </div>
            {{/if}}
        </div>
    </script>
<?php
$noteLogs = \yii\helpers\Url::toRoute(['contacts-log/index']);
$statusLogs = \yii\helpers\Url::toRoute(['contacts-log/status']);
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
            if(typeof _contact_id == "undefined"){
                _contact_id = $('.grid-view').yiiGridView('getSelectedRows');
            }
            _form.append("contact_id",_contact_id);
          
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
    
    
    
     $("#changeStatus").click(function() {
           var keys = $('.grid-view').yiiGridView('getSelectedRows');
            if(keys.length <= 0){
                swal.fire({
                    title : "Thông báo",
                    text : "Để thay đổi trạng thái hãy chọn liên hệ",
                    icon : "error",
                });
                return;
            }
             $("#takeNoteModal").modal();
            $.ajax({
            type: 'POST',
            data : {cid : null},
            url : "$noteLogs",
            success : function(res) {
                if(res.success){
                    $("#resultNote").html(compileTemplate("note-template",res))
                }
            }
        })
        })
        $("body").on("change","select[name='status']",function() {
            let _val = $(this).val();
            switch (_val) {
              case "callback":
                  case "pending":
                      $(".callback-group").show()
                      break;
                      default:
                             $(".callback-group").hide()
                          break;
            }
        });
JS;
$this->registerJs($js);