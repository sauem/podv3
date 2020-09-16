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
        <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Tên sheet nhập liệu " contacts
            "<br></small>
        <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Nhập file excel có định dạng xlsx,
            csv<br></small>
        <small class="text-center text-danger"><i class="fa fa-sticky-note"></i> Số dòng tối đa 50.000 dòng</small>
        <?php ActiveForm::end() ?>
    </div>
    <script id="excel-template" type="text/x-handlebars-template">
        <div class="table-responsive">
            <table class="viewTable table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th colspan="3">
                        <input class="form-control filterContact" type="text"
                               placeholder="Tìm tên, SĐT, zipcode, ip, page..."/>
                    </th>
                    <th colspan="1">
                        <button type="button" class="btn removeAllRowChecked btn-sm btn-warning"><i
                                    class="fe-trash"></i> Xoá dữ liệu đã chọn
                        </button>
                    </th>
                    <th>
                        <button type="button" class="btn viewWarningRow btn-sm btn-warning">
                            {{this.warning.total}} <i class="fe-bell"></i> Cảnh báo
                        </button>
                    </th>
                    <th colspan="2">
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
                            <button data-key="{{@index}}" type="button" data-toggle="tooltip" title="Sửa liên hệ"
                                    class="btn editRowImport btn-sm btn-info">
                                <i
                                        class="fa fa-edit"></i></button>
                        </div>
                    </td>
                    <td>
                        {{code}}<br>
                        {{name}}<br>
                        SĐT : {{phone}} <br>
                        Đăng kí : {{date register_time}}<br>
                        Status: {{span status}}
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
    <script id="row-import-template" type="text/x-handlebars-template">
        <form id="rowForm" data-key="{{key}}">
            <div class="row">
                <div class="form-group col-12">
                    <label>Tên</label>
                    <input name="name" value="{{name}}" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>SĐT</label>
                    <input name="phone" value="{{phone}}" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>Zipcode</label>
                    <input name="zipcode" value="{{zipcode}}" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>Quốc gia</label>
                    <input name="country" value="{{country}}" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>Loại contact</label>
                    <input name="type" value="{{type}}" class="form-control">
                </div>
                <div class="form-group col-12">
                    <label>Địa chỉ</label>
                    <input name="address" value="{{address}}" class="form-control">
                </div>

                <div class="form-group col-12">
                    <label>Yêu cầu</label>
                    <textarea name="option" class="form-control">{{option}}</textarea>
                </div>
                <div class="form-group col-12">
                    <label>Ghi chú</label>
                    <textarea name="note" class="form-control">{{note}}</textarea>
                </div>
            </div>
            <input type="hidden" name="host" value="{{host}}" class="form-control">
            <input type="hidden" name="ip" value="{{ip}}" class="form-control">
            <input type="hidden" name="link" value="{{link}}" class="form-control">
            <input type="hidden" name="register_time" value="{{register_time}}" class="form-control">
            <input type="hidden" name="utm_campaign" value="{{utm_campaign}}" class="form-control">
            <input type="hidden" name="utm_content" value="{{utm_content}}" class="form-control">
            <input type="hidden" name="utm_medium" value="{{utm_medium}}" class="form-control">
            <input type="hidden" name="utm_source" value="{{utm_source}}" class="form-control">
            <input type="hidden" name="utm_term" value="{{utm_term}}" class="form-control">
        </form>
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
                            _keys.push(parseInt(_key));
                            $(this).closest("tr").remove();
                        }
                  });
                 window.EXCEL.rows = EXCEL.rows.filter( (item , index) => !_keys.includes(index));
                 window.EXCEL.total = EXCEL.rows.length;
                 toastr.success("Xoá hàng thành công!");
                 renderViewTemplate("result", "excel-template", window.EXCEL);
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
    
    $("body").on("click",".editRowImport",function() {
        let _key = $(this).data("key");
        $("#editRowModal").modal({ "backdrop" : 'static', "keyboard" : false});
        let _row = EXCEL.rows[_key];
        _row["key"] = _key;
        
        console.log(_row)
        if(_row){
            $("#resultRowImport").html(compileTemplate("row-import-template", _row));   
        }
    });
     $(".saveRowImport").click(function() {
            let _form  = $("#rowForm").serializeArray();
            let _key = $("#rowForm").data("key");
            let _row = {}
            $.each( _form , (index , item ) => {
                if(item.name == "key"){
                     return true;
                }
                _row[item.name] = item.value;
            })
            EXCEL.rows[_key] = _row;
            EXCEL.total = EXCEL.rows.length;
            renderViewTemplate("result", "excel-template", EXCEL);
            $("#editRowModal").modal("hide");
            toastr.success("Thay đổi thanh công!");
    });
     $("body").on("click", ".viewWarningRow", function () {
         $("#errorRowModal").modal({backdrop : 'static'});
         $("#resultErrorRowImport").html(compileTemplate("error-template", EXCEL.warning.data));    
     });
JS;
$this->registerJs($js);