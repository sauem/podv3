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
            <table class="viewTable table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th colspan="4">
                        <input class="form-control filterContact" type="text"
                               placeholder="Tìm tên, SĐT, zipcode, ip, page..."/>
                    </th>
                    <th colspan="1">
                        <button type="button" class="btn removeAllRowChecked btn-sm btn-warning"><i
                                    class="fa fa-trash"></i> Xoá dữ liệu đã chọn
                        </button>
                    </th>
                    <th colspan="3">
                        <strong>Exel size : {{toMb this.size}}</strong>
                        <br>
                        <strong>Số lượng liên hệ : {{this.total}}</strong>
                    </th>

                </tr>
                <tr>
                    <th></th>
                    <th>Thao tác</th>
                    <th width="20%">Liên hệ</th>
                    <th>Địa chỉ</th>
                    <th>trang đặt hàng</th>
                    <th>Ghi chú</th>
                    <th>Loại contact</th>
                </tr>
                </thead>
                <tbody>
                {{#each this.rows}}
                <tr>
                    <td><input value="{{@index}}" type="checkbox"></td>
                    <td>
                        <div class="btn-group">
                            <button data-key="{{@index}}" type="button" data-toggle="tooltip" title="Xoá liên hệ"
                                    class="removeRowImport btn btn-sm btn-warning"><i
                                        class="fa fa-trash"></i></button>
                            <button type="button" data-toggle="tooltip" title="Sửa liên hệ" class="btn btn-sm btn-info">
                                <i
                                        class="fa fa-edit"></i></button>
                        </div>
                    </td>
                    <td>
                        {{name}}<br>
                        SĐT : {{phone}} <br>
                        Đăng kí : {{date register_time}}
                    </td>
                    <td>
                        Đ/C : {{address}}<br>
                        zipcode : <strong class="badge badge-info">{{zipcode}}</strong><br>
                        IP: {{ip}}
                    </td>
                    <td>
                        Trang : {{link}}<br>
                        Y/C : {{option}}
                    </td>
                    <td>{{note}}</td>
                    <td>{{type}}</td>

                </tr>
                {{/each}}
                </tbody>
            </table>
        </div>
    </script>
<?php
$js = <<<JS
    $("body").on("keyup",".filterContact",function(e) {
        e.preventDefault();
        let _val = $(this).val().toLowerCase();;
        $(".viewTable tbody > tr").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(_val) > -1)
            });
    });
    $("body").on("click",".removeAllRowChecked",function() {
        let _keys = [];
        let _checks = $("table.viewTable > tbody > tr > td > input[type='checkbox']");
        let Rows = EXCEL.rows;
        swal.fire({
             title : "Cảnh báo!",
             icon : "info",
             text : "Xoá hàng dữ liệu này?",
             showCancelButton: true,
             cancelButtonText:  "Hủy",
             confirmButtonText: "Đồng ý",
        }).then((value) =>{
            if(value.value){
                
                  $.each(_checks, function() {
                        if($(this).is(":checked")){
                            let _key = $(this).val();
                            window.EXCEL.rows =  Rows.filter( (item , index) => index !== _key);
                            $(this).closest("tr").remove();
                        }
                  });
                 
                 window.EXCEL.total = EXCEL.rows.length;
                 toastr.success("Xoá hàng thành công!");
                // renderViewTemplate("result", "excel-template", window.EXCEL);
            }
        })
       
    });
    $("body").on("click",".removeRowImport",function() {
        let _key = $(this).data("key");
        swal.fire({
             title : "Cảnh báo!",
             icon : "info",
             text : "Xoá hàng dữ liệu này?",
             showCancelButton: true,
             cancelButtonText:  "Hủy",
             confirmButtonText: "Đồng ý",
        }).then((value) =>{
            if(value.value){
                window.EXCEL.rows = EXCEL.rows.filter( (item , index) => index !== _key);
                window.EXCEL.total = EXCEL.rows.length;
                $(this).closest("tr").remove();
                toastr.success("Xoá hàng thành công!");
                renderViewTemplate("result", "excel-template", EXCEL);
            }
        })
    });
JS;
$this->registerJs($js);