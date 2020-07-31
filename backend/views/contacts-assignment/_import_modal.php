<?php

use yii\widgets\ActiveForm;

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
<script id="excel-template" type="text/x-handlebars-template">
    <div class="table-responsive">
        <table class="fixed_header table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th colspan="7" class="text-center">Thông tin dữ liệu</th>
            </tr>
            </thead>
<!--            <tbody>-->
<!--            {{#each this.rows}}-->
<!--            <tr>-->
<!--                <td>{{name}}</td>-->
<!--                <td>{{phone}}</td>-->
<!--                <td>{{address}}</td>-->
<!--                <td>{{zipcode}}</td>-->
<!--                <td>{{link}}</td>-->
<!--                <td>{{option}}</td>-->
<!--                <td>{{ip}}</td>-->
<!--                <td>{{note}}</td>-->
<!--            </tr>-->
<!--            {{/each}}-->
<!--            </tbody>-->
            <tfoot>
            <tr>
                <td>Exel size:</td>
                <td colspan="2">{{toMb this.size}}</td>
                <td colspan="2">Số lượng liên hệ:</td>
                <td colspan="2">{{this.total}}</td>
            </tr>
            </tfoot>
        </table>
    </div>
</script>
