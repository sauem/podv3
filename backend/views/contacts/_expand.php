<?php

use kartik\form\ActiveForm;
use yii\helpers\Url;

?>
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin([
                'id' => 'noteForm_' . $model->id,
                'action' => Url::toRoute(['contacts-log/create'])
            ]) ?>
            <div id="resultNote_<?= $model->id ?>">

            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>

    <script id="note-template" type="text/x-handlebars-template">
        <div class="row">
            <div class="col-md-5">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Trạng thái hiện tại</label>
                            <select name="status" class="form-control select2">
                                {{#each this.status}}
                                {{#unless (isNull @key)}}
                                <option {{selected @key ..
                                /selected}} value="{{@key}}">{{this}}
                                </option>
                                {{/unless }}
                                {{/each}}
                            </select>
                            <input type="hidden" name="user_id" value="<?= Yii::$app->user->getId() ?>">
                            <input type="hidden" name="contact_id" value="{{this.key.cid}}">
                            <input type="hidden" name="phone" value="<?= Yii::$app->request->get('phone') ?>">
                        </div>
                        <div class="form-group callback-group" style="display: none">
                            <label>Goị lại sau (giờ):</label>
                            <input class="form-control" type="number" name="callback_time"
                                   placeholder="Gọi lại sau 3 giờ">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Ghi chú</label>
                            <textarea class="form-control" name="note"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button data-pjax="0" data-key="{{this.key.cid}}" type="button"
                                class="btn submitLog btn-success btn-sm">Lưu
                            ghi chú
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
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
            </div>
        </div>
    </script>

<?php
$noteLogs = Url::toRoute(['contacts-log/index']);
$statusLogs = Url::toRoute(['contacts-log/status']);
$js = <<<JS

        $("body").find(".grid-view").on('kvexprow:toggle', function (event, ind, key, extra) {
            __loadContactLogs(key);
        });
    
    function __loadContactLogs(k) {
        $.ajax({
            type: 'POST',
            data : {cid : k},
            url : "$noteLogs",
            success : function(res) {
                if(res.success){
                    $("#resultNote_" + k).html(compileTemplate("note-template",res))
                }
            }
        })  
    };
    
    $("body").on("click",".submitLog",function(e) {
        let _key = $(this).data('key');
        let _form = $(this).closest("#noteForm_"+ _key );
        let _url = _form.attr("action");
        let _formData = new FormData(_form[0]);

        if((_formData.get("status") == "pending" ||
         _formData.get("status") == "callback") && 
         _formData.get("callback_time") == ""){
            toastr.warning("Hãy đặt thời gian gọi lại!");
            return false;
        }
        $.ajax({    
            url : _url,
            data : _formData,
            type : 'POST',
            cache : false,
            contentType: false,
            processData: false,
            success : function(res) {
                if(res.success){
                    swal.fire('Thông báo!',"Thêm trạng thái thành công.","success").then(() => {window.location.reload()})
                    return false;              
                }else{
                    toastr.warning(res.msg);
                }
            }
        });
        return false;
    });
    
    
    $("body").on("change","select[name='status']",function() {
            let _val = $(this).val();
            switch (_val) {
              case "callback":
                  case "pending":
                      $(".callback-group").show()
                      $(".callback-group").find("input[name='callback_time']").attr("required",true);
                      break;
                      default:
                             $(".callback-group").find("input[name='callback_time']").attr("required",false);
                             $(".callback-group").hide()
                          break;
            }
        });
JS;
$this->registerJs($js);