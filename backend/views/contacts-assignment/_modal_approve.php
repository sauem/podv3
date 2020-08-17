<?php

use kartik\form\ActiveForm;
use backend\models\UserModel;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $form = ActiveForm::begin([
    'id' => 'approveForm',
    'action' => Url::toRoute(['ajax/approve-phone'])
]) ?>
    <div class="modal fade" id="modalApprove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Phân bổ số điển thoại</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= Html::label("Tài khoản phân bổ") ?>
                                <br>
                                <?= Html::dropDownList("user_id", null, UserModel::listSales(), [
                                    'class' => 'form-control select2'
                                ]) ?>
                            </div>
                            <p>Số điện thoại được chọn</p>
                            <div id="resultPhone"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
    <script type="text/x-handlebars-template" id="phone-template">
        {{#each this}}
        <span data-phone="{{this.phone}}" class="badge badge-info my-1 p-2">
            {{this.phone}} | {{this.country}}
            <a href="javascript:;" class="text-warning removePhone">
                <i class="fa fa-times"></i>
            </a>
        </span>
        {{/each}}
    </script>
<?php
$js = <<<JS
    $("body").on("click",".removePhone",function() {
        let _phone = $(this).parent().data("phone");
        let cof = confirm("Loại bỏ số điện thoại này?");
        if(cof){
            window.PHONES = PHONES.filter( item => item.phone != _phone);
            $(this).parent().remove();
        }
        if(PHONES.length <= 0){
            $("#modalApprove").modal("hide");
        }
         $("#resultPhone").html(compileTemplate("phone-template", window.PHONES));
    });

    $(document).on("beforeSubmit","#approveForm",function(res) {
        res.preventDefault();
            let _phones = window.PHONES;
            let _user_id = $(this).find("select[name='user_id']").val();
            let _action = $(this).attr("action");
            
            $.ajax({
                url : _action,
                type : "POST",
                cache : false,
                data : { phones : _phones, user : _user_id },
                success : function(res) {
                   
                    if(res.success){
                        toastr.success("áp dụng thành công!");
                            $("#modalApprove").modal("hide");
                        __reloadData();
                        return false;
                    }
                    toastr.warning(res.msg);
                }
            });
        return false;
    })
JS;
$this->registerJs($js);