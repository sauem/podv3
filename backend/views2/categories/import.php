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
    <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Tên sheet nhập liệu "categories" <br></small>
    <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Nhập file excel có định dạng xlsx, csv<br></small>
    <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Số dòng tối đa 50.000 dòng</small>
    <?php ActiveForm::end() ?>
</div>
<script id="categories-template" type="text/x-handlebars-template">
    <div class="table-responsive">
        <table class="fixed_header table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>Tên loại sản phẩm</th>
                <th>Mô tả</th>

            </tr>
            </thead>
            <tbody>
            {{#each this.rows}}
            <tr>
                <td>{{name}}</td>
                <td>{{description}}</td>
            </tr>
            {{/each}}
            </tbody>
            <tfoot>
            <tr>
                <td>Exel size: {{toMb this.size}}</td>
                <td>Số lượng hàng: {{this.total}}</td>
            </tr>
            </tfoot>
        </table>
    </div>
</script>
