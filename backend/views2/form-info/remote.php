<?php

use backend\models\CategoriesModel;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use backend\models\ProductsModel;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin([
    'id' => 'infoForm',
    //'action' => Url::toRoute(['contacts-assignment/save-option'])
]) ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'category_id')->dropDownList(
                CategoriesModel::select(),
                ['class' => 'select2 form-control']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'revenue')->label('Doanh thu') ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'content')->textarea(['placeholder' => 'Nội dung']) ?>
            <?= $form->field($model, 'old_content')->hiddenInput(['value' => $model->content])->label(false) ?>
        </div>
        <?php
        $skus = $model->skus ? $model->skus : false;
        ?>
        <div id="form-sku" class="col-12">
            <div class="mt-2 item-sku row">
                <div class="col-md-6">
                    <?= Html::dropDownList('info[1][sku]', $skus[0] ? $skus[0]->sku : "",
                        ProductsModel::select("sku", "sku"),
                        ['class' => 'select2 form-control']
                    ) ?>
                </div>
                <div class="col-md-6">
                    <?= Html::textInput('info[1][qty]', $skus[0] ? $skus[0]->qty : "", ['required' => true, 'placeholder' => "Số lượng", 'class' => 'form-control']) ?>
                </div>
            </div>
            <div class="mt-2 item-sku row">
                <div class="col-md-6">
                    <?= Html::dropDownList('info[2][sku]', isset($skus[1]) ? $skus[1]->sku : "",
                        ProductsModel::select("sku", "sku"),
                        ['class' => 'select2 form-control']
                    ) ?>
                </div>
                <div class="col-md-6">
                    <?= Html::textInput('info[2][qty]', isset($skus[1]) ? $skus[1]->qty : "", ['placeholder' => "Số lượng", 'class' => 'form-control']) ?>
                </div>
            </div>
            <div class="mt-2 item-sku row">
                <div class="col-md-6">
                    <?= Html::dropDownList('info[3][sku]', isset($skus[2]) ? $skus[2]->sku : "",
                        ProductsModel::select("sku", "sku"),
                        ['class' => 'select2 form-control']
                    ) ?>
                </div>
                <div class="col-md-6">
                    <?= Html::textInput('info[3][qty]', isset($skus[2]) ? $skus[2]->qty : "", ['placeholder' => "Số lượng", 'class' => 'form-control']) ?>
                </div>
            </div>
            <div class="mt-2 item-sku row">
                <div class="col-md-6">
                    <?= Html::dropDownList('info[4][sku]', isset($skus[3]) ? $skus[3]->sku : "",
                        ProductsModel::select("sku", "sku"),
                        ['class' => 'select2 form-control']
                    ) ?>
                </div>
                <div class="col-md-6">
                    <?= Html::textInput('info[4][qty]', isset($skus[3]) ? $skus[3]->qty : "", ['placeholder' => "Số lượng", 'class' => 'form-control']) ?>
                </div>
            </div>
            <div class="mt-2 item-sku row">
                <div class="col-md-6">
                    <?= Html::dropDownList('info[5][sku]', isset($skus[4]) ? $skus[4]->sku : "",
                        ProductsModel::select("sku", "sku"),
                        ['class' => 'select2 form-control']
                    ) ?>
                </div>
                <div class="col-md-6">
                    <?= Html::textInput('info[5][qty]', isset($skus[4]) ? $skus[4]->qty : "", ['placeholder' => "Số lượng", 'class' => 'form-control']) ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>
<?php
$js = <<<JS
 $(document).on("beforeSubmit","#infoForm",function(event) {
        event.preventDefault();
        let _form = new FormData($(this)[0]);
        let _infoID = "$model->id";
        _form.append("info_id",_infoID);
        let _olContent = "$model->content";
     
        swal.fire({
            title : "Đang tạo mẫu đơn",
            icon : "info",
            onBeforeOpen : () => {
                  swal.showLoading();
                $.ajax({
                        url : config.saveFormInfo,
                        type : "POST",
                        processData: false,
                        contentType : false,
                        data  : _form,
                        success : function(res) {
                            
                            if(res.success){
                                __updateContactWaiting( _olContent, res.model.content);
                                 swal.close();
                               return false;
                            }
                            toastr.warning(res.msg);
                            toastr.info("Cập nhật liên hệ có yêu cầu");
                             __updateContactWaiting(_olContent, res.model.content);
                             swal.close();
                        }
                    })
            }
        })
        return false;
    });

    function __updateContactWaiting(oldContent , newContent){
        console.log("OLD:" , oldContent)
        console.log("NEW:" , newContent)
        swal.fire({
            title : "Đang cập nhật liên hệ!",
            icon : "info",
            onBeforeOpen : () => {
                swal.showLoading();
                $.ajax({
                        url : config.updateContactWaiting,
                        type : "POST",
                        cache :false,
                        data  : {oldContent : oldContent, newContent : newContent},
                        success : function(res) {
                            if(res.success){
                                toastr.success(res.msg);
                                __reloadData();
                                $("body").find("#optionModal").modal("hide");
                            }else{
                               toastr.warning(res.msg);
                            }
                        }
                    })
            }
        })
    }
JS;
$this->registerJs($js);