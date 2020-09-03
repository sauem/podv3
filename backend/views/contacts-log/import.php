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
    <small class="text-center text-warning"><i class="fa fa-sticky-note"></i> Nhập file excel có định dạng xlsx, csv<br></small>
    <small class="text-center text-warning"><i class="fa fa-sticky-note"></i> Số dòng tối đa 5.000 dòng</small>
    <?php ActiveForm::end() ?>
</div>
<script id="logs-template" type="text/x-handlebars-template">
    <div class="table-responsive">
        <table class="viewTable table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th colspan="8">
                    <strong>Exel size : {{toMb this.size}}</strong>
                    <br>
                    <strong>Số lượng liên hệ : {{this.total}}</strong>
                </th>
            </tr>
            <tr>
                <th>Số điện thoại</th>
                <th width="15%">Trang đích</th>
                <th width="20%">Yêu cầu</th>
                <th>Lần gọi 1</th>
                <th>Lần gọi 2</th>
                <th>Lần gọi 3</th>
                <th>Lần gọi 4</th>
                <th>Lần gọi 5</th>
            </tr>
            </thead>
            <tbody>
            {{#each this.rows}}
            <tr>
                <td>{{phone}}</td>
                <td>{{link}}</td>
                <td>{{option}}</td>
                {{#each this.called}}
                    <td>{{span this.status}}
                        {{#if this.note}}
                        <br><small class="text-info">Note: <br>{{this.note}}</small>
                        {{/if}}
                    </td>
                {{/each}}
            </tr>
            {{/each}}
            </tbody>
        </table>
    </div>
</script>