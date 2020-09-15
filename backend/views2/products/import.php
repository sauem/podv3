<?php

use kartik\form\ActiveForm;

?>

<div class="import-area">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'formUpload',
            'enctype' => 'multipart/form-data'
        ]
    ]) ?>
    <div class="input-file">
        <p class="text-note"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Kéo thả hoặc chọn file excel của
            bạn</p>
        <?= $form->field($model, 'excelFile')->fileInput(['onchange' => 'onUpload(this)'])->label(false) ?>
    </div>
    <div id="result"></div>
    <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Tên sheet nhập liệu "product" <br></small>
    <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Nhập file excel có định dạng xlsx, csv<br></small>
    <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Số dòng tối đa 5.000 dòng</small>
    <?php ActiveForm::end() ?>
</div>
<script id="product-template" type="text/x-handlebars-template">
    <div class="table-responsive">
        <table class="fixed_header table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Mã sản phẩm</th>
                <th>loại sản phẩm</th>
                <th>Giá sản phẩm</th>
                <th>Thuộc tính</th>
            </tr>
            </thead>
            <tbody>
            {{#each this.rows}}
            <tr>
                <td>{{name}}</td>
                <td>{{sku}}</td>
                <td>{{category}}</td>
                <td>{{regular_price}}</td>
                <td>{{option}}</td>
            </tr>
            {{/each}}
            </tbody>
            <tfoot>
            <tr>
                <td>Exel size:</td>
                <td colspan="2">{{toMb this.size}}</td>
                <td>Số lượng sản phẩm:</td>
                <td>{{this.total}}</td>
            </tr>
            </tfoot>
        </table>
    </div>
</script>
