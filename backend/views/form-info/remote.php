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
        <div><small class="text-center text-warning"><i class="fa fa-sticky-note"></i> Nhập file excel có định dạng xlsx, csv<br></small>
            <small class="text-center text-warning"><i class="fa fa-sticky-note"></i> Số dòng tối đa 5.000 dòng</small>
            <checkbox></checkbox>
        </div>
        <div>
            <input type="checkbox" checked name="createNewIfNotExists">
            <small class="text-right text-warning">Tạo sản phẩm/loại sản phẩm mới nếu chưa tồn tại trong hệ thống!<br>
                Tên sản phẩm cần được điều chỉnh lại sau khi tạo tự động với chức năng này!
            </small>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script id="order-template" type="text/x-handlebars-template">
    <div class="table-responsive">
        <table class="fixed_header table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>loại sản phẩm</th>
                <th width="30%">Nội dung</th>
                <th>Doanh thu</th>
                <th>Sản phẩm</th>
            </tr>
            </thead>
            <tbody>
            {{#each this.rows}}
            <tr>
                <td>{{category}}</td>
                <td>{{content}}</td>
                <td>{{money revenue}}</td>
                <td>
                    {{#each skus}}
                    {{#if (notNull sku)}}
                    <small>{{qty}}*{{sku}}</small>
                    <br>
                    {{/if}}
                    {{/each}}
                </td>
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
