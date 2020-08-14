<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\form\ActiveForm;

$this->title = 'Form Infos';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-md-5">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Tạo mẫu</h2>
                </div>
                <div class="ibox-body">
                    <?php $form = ActiveForm::begin() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'category_id')->dropDownList(
                                \backend\models\CategoriesModel::select(),
                                ['class' => 'select2 form-control']
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'revenue')->label('Doanh thu') ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($model, 'content')->textarea(['placeholer' => 'Nội dung']) ?>
                        </div>
                        <div id="form-sku" class="col-12">
                            <div class="mt-2 item-sku row">
                                <div class="col-md-6">
                                    <?= Html::dropDownList('info[1][sku]', '',
                                        \backend\models\ProductsModel::select("sku", "sku"),
                                        ['class' => 'select2 form-control']
                                    ) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::textInput('info[1][qty]', '', ['placeholder' => "Số lượng",'class' => 'form-control']) ?>
                                </div>
                            </div>
                            <div class="mt-2 item-sku row">
                                <div class="col-md-6">
                                    <?= Html::dropDownList('info[2][sku]', '',
                                        \backend\models\ProductsModel::select("sku", "sku"),
                                        ['class' => 'select2 form-control']
                                    ) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::textInput('info[2][qty]', '', ['placeholder' => "Số lượng",'class' => 'form-control']) ?>
                                </div>
                            </div>
                            <div class="mt-2 item-sku row">
                                <div class="col-md-6">
                                    <?= Html::dropDownList('info[3][sku]', '',
                                        \backend\models\ProductsModel::select("sku", "sku"),
                                        ['class' => 'select2 form-control']
                                    ) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::textInput('info[3][qty]', '', ['placeholder' => "Số lượng",'class' => 'form-control']) ?>
                                </div>
                            </div>
                            <div class="mt-2 item-sku row">
                                <div class="col-md-6">
                                    <?= Html::dropDownList('info[4][sku]', '',
                                        \backend\models\ProductsModel::select("sku", "sku"),
                                        ['class' => 'select2 form-control']
                                    ) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::textInput('info[4][qty]', '', ['placeholder' => "Số lượng",'class' => 'form-control']) ?>
                                </div>
                            </div>
                            <div class="mt-2 item-sku row">
                                <div class="col-md-6">
                                    <?= Html::dropDownList('info[5][sku]', '',
                                        \backend\models\ProductsModel::select("sku", "sku"),
                                        ['class' => 'select2 form-control']
                                    ) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::textInput('info[5][qty]', '', ['placeholder' => "Số lượng",'class' => 'form-control']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class=" mt-4 text-right">
                                <?= Html::resetButton("Làm mơi", ['class' => 'btn btn-warning']) ?>
                                <?= Html::submitButton("Lưu", ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Danh sách mẫu</h2>
                </div>
                <div class="ibox-body">

                </div>
            </div>
        </div>
    </div>
<?php
$js = <<<JS
    window.INFO =  {
        count : 1,
        infos : []    
    }
    $("#addItem").click(function() {
        let _item = $(".item-sku")[0].outerHTML;
        if(INFO.count >= 5){
            toastr.warning("Giới hạn mã sản phẩm không vượt quá 5!");
            return false;
        }
        INFO.count = INFO.count + 1;
        $("#form-sku").append(_item);
    });
    $("body").on("click",".removeItem", function() {
        if(INFO.count <= 1){
            toastr.warning("Mã sản phẩm tối thiểu là 1");
            return false;
        }
        $(this).parent().parent().remove();
        INFO.count = INFO.count - 1;
    })
JS;
$this->registerJs($js);