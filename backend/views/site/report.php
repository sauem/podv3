<?php

use yii\helpers\Html;
use backend\models\UserModel;
use kartik\form\ActiveForm;
use backend\models\ContactsModel;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;

?>
<?php $form = ActiveForm::begin([
    'id' => 'reportForm',
    'action' => Url::toRoute(['ajax/report-search'])
]) ?>
    <div class="ibox">
        <div class="ibox-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Tài khoản phân bổ</label>
                    <?= Html::dropDownList("account[]", null, UserModel::listSales(), [
                        'prompt' => 'Chọn tài khoản',
                        'multiple' => true,
                        'class' => 'select2 form-control'
                    ]) ?>
                </div>
                <div class="col-md-3 form-group">
                    <label>Lọc theo</label>
                    <?= Html::dropDownList("type", null, [
                        'Doanh thu',
                        'Liên hệ',
                    ], [
                        'class' => 'select2 form-control'
                    ]) ?>
                </div>
                <div class="col-md-3 form-group">
                    <label>Trạng thái liên hệ</label>
                    <?= Html::dropDownList("status[]", null,
                        ContactsModel::STATUS
                        , [
                            'prompt' => 'Chọn tài khoản',
                            'multiple' => true,
                            'class' => 'select2 form-control'
                        ]) ?>
                </div>
                <div class="col-md-3 form-group">
                    <?php
                    echo '<label class="control-label">Ngày tạo đơn</label>';
                    echo '<div class="drp-container">';
                    echo DateRangePicker::widget([
                        'name'=>'created_at',
                        'presetDropdown'=>true,
                        'convertFormat'=>true,
                        'includeMonthsFilter'=>true,
                        'pluginOptions' => ['locale' => ['format' => 'm-d-Y']],
                        'options' => ['placeholder' => 'Chọn ngày tạo đơn']
                    ]);
                    echo '</div>';

                    ?>
                </div>
                <div class="col-md-12 form-group text-right">
                    <?= Html::submitButton('Tìm kiếm', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                    <div id="result">
                        <p class="text-center">Không có kết quả tìm kiếm nào...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>
<?php
$js = <<<JS
    $('.input-daterange').daterangepicker()
    $(document).on("beforeSubmit","#reportForm",function(e) {
        e.preventDefault()
            let _form = new FormData($(this)[0])
            let _action = $(this).attr("action")
            
           fetchResult(_form,_action)
      
        return false
    });
    
    function fetchResult(_form, _action) {
        loading()
        $.ajax({
            type : "POST",
            url : _action,
            data : _form,
            contentType: false,
            processData: false,
            success : function(res) {
              endLoading()
              if(res){
                  console.log(res)
              }else{
                  $("#result").html('<p class="text-center">Không có kết quả tìm kiếm nào...</p>')
              }
            },
            error : function(res) {
                endLoading()
            }
        })
    }
    
    function loading() {
        $("#result").html("<div class='loading text-center'><i class=\"fa fa-refresh fa-2x fa-fw fa-spin\"></i></div>");
    }
    function endLoading() {
      $("#result").find(".loading").remove();
    }
JS;
$this->registerJs($js);
