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
    <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Tên sheet nhập "logs"<br></small>
    <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Nhập file excel có định dạng xlsx, csv<br></small>
    <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Số dòng tối đa 50.000 dòng</small>
    <?php ActiveForm::end() ?>
</div>
<script id="logs-template" type="text/x-handlebars-template">
    <div class="table-responsive">
        <table class="viewTable table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th colspan="10">
                    <div class="d-flex justify-content-between">
                        <p>Exel size : {{toMb this.size}}</p>
                        <p>Số lượng liên hệ : {{this.total}}</p>
                    </div>
                </th>
            </tr>
            <tr>
                <th>Contact code</th>
                <th>Thời gian gọi</th>
                <th width="20%">Địa chỉ</th>
                <th>Zipcode</th>
                <th>Loại sản phẩm</th>
                <th>Yêu cầu đặt hàng</th>
                <th>Ghi chú của khách</th>
                <th>Kết quả cuộc gọi</th>
            </tr>
            </thead>
            <tbody>
            {{#each this.rows}}
            <tr>
                <td>{{code}} <br>{{phone}}</td>
                <td>{{date time_call}}</td>
                <td>{{address}}</td>
                <td>{{country}}|{{zipcode}}</td>
                <td>{{category}}</td>
                <td>
                    {{link}}<br>
                    {{option}}
                </td>
                <td>{{customer_note}}</td>
                <td>{{span this.status}}
                    {{#if this.note}}
                    <br><small class="text-info">Note: <br>{{this.note}}</small>
                    {{/if}}
                </td>
            </tr>
            {{/each}}
            </tbody>
        </table>
    </div>
</script>