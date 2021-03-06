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
    <div class="d-flex justify-content-between">
        <div>
            <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Tên sheet nhập liệu  "zipcode"<br></small>
            <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Nhập file excel có định dạng xlsx, csv<br></small>
            <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Số dòng tối đa 5.000 dòng</small>
        </div>

    </div>
    <?php ActiveForm::end() ?>
</div>
<script id="zipcode-template" type="text/x-handlebars-template">
    <div class="table-responsive">
        <table class="fixed_header table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>Quốc gia/zipcode</th>
                <th>Thành phố</th>
                <th>Quận huyện</th>
                <th  width="30%">Địa chỉ</th>
            </tr>
            </thead>
            <tbody>
            {{#each this.rows}}
            <tr>
                <td class="text-center">
                    {{country_name}}
                    <hr>{{country_code}}| {{zipcode}}
                </td>
                <td>{{city}}</td>
                <td>{{district}}</td>
                <td>{{address}}</td>
            </tr>
            {{/each}}
            </tbody>
            <tfoot>
            <tr>
                <td>Exel size:</td>
                <td>{{toMb this.size}}</td>
                <td>Số lượng sản phẩm:</td>
                <td>{{this.total}}</td>
            </tr>
            </tfoot>
        </table>
    </div>
</script>
