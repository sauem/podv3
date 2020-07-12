<?php

use backend\models\ContactsModel;
use kartik\form\ActiveForm;

?>
    <div class="modal fade" data-keyboard="false" data-backdrop="static" id="takeOrder" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
    <div class="modal-dialog modal-xl modal-lg" role="document">
        <?php $form = ActiveForm::begin([
            'id' => 'formOrder',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            // 'validationUrl' => \yii\helpers\Url::toRoute(['orders/create-validate']),
            'action' => \yii\helpers\Url::toRoute(['orders/create'])
        ]) ?>
        <input type="hidden" value="<?= Yii::$app->user->getId()?>" name="user_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tạo đơn hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-info  m-t-10"><i class="fa fa-bar-chart"></i> Thông tin khách hàng
                                </h5>
                                <small class="text-danger">(*) các thông tin bắt buộc</small>
                            </div>
                            <div id="resultInfo" class="col-12">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fa fa-bar-chart"></i> Sản phẩm đặt mua</h5>
                        <div class="d-flex justify-content-between">
                            <div id="resultProduct">
                            </div>
                        </div>
                        <hr>
                        <div>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <td width="30%">Sản phẩm</td>
                                    <td width="30%">Option</td>
                                    <td width="20%">số lượng</td>
                                    <td width="15%" class="text-right">đơn giá</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody id="resultItemProduct">

                                </tbody>
                                <tfoot id="totalResult">

                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>

    <script type="text/x-handlebars-template" id="template-product">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tên khách hàng</label>
                    <input name="customer_name" value="{{this.name}}" class="form-control">
                    <input type="hidden" name="contact_id" value="{{this.id}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Số điện toại</label>
                    <input name="customer_phone" value="{{this.phone}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email</label>
                    <input name="customer_email" value="{{this.email}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input name="address" value="{{this.address}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Quận/huyện</label>
                    <input name="district" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Thành phố</label>
                    <input name="city" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Zipcode</label>
                    <input name="zipcode" value="{{this.zipcode}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Quốc gia</label>
                    <input name="country" class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Ghi chú đơn hàng</label>
                    <textarea name="order_note" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </script>
    <script type="text/x-handlebars-template" id="template-sku">
        <div class="form-group">
            <select class="form-control select2">
                {{#each this}}
                <option value="{{this.sku}}">{{this.sku}} - {{this.name}}</option>
                {{/each}}
            </select>
            <button type="button" id="addProduct" class="btn btn-success">Thêm sản phẩm</button>
        </div>
    </script>

    <script type="text/x-handlebars-template" id="template-item-product">

        <tr class="form-group">
            <td>{{name}}<br>
                <small>{{category.name}}|{{sku}}</small>
                <input type="hidden" value="{{sku}}" name="product[{{sku}}][product_sku]">
            </td>
            <td>
                <select class="form-control" name="product[{{sku}}][product_option]">
                    {{#each this.option}}
                    <option value="{{this}}">{{this}}</option>
                    {{/each}}
                </select>
            </td>
            <td>
                <input data-sku="{{sku}}"  class="form-control" style="width: 80px;" type="number" name="product[{{sku}}][qty]"
                       value="1">
            </td>
            <td class="text-right">
                {{regular_price}}<br>
                <small>-{{sale_price}}</small>
            </td>
            <td>
                <button data-sku="{{sku}}" type="button" class="removeItem btn btn-xs btn-danger">xoá</button>
            </td>
        </tr>

    </script>
    <script type="text/x-hanldebars-template" id="total-template">
        <tr>
            <td><strong>Tổng hóa đơn</strong></td>
            <td colspan="3" class="text-right">
                <strong>{{money this.total}}đ</strong>
                <input type="hidden" value="{{subTotal}}" name="sub_total">
                <input type="hidden" value="{{saleTotal}}" name="sale">
                <input type="hidden" value="{{total}}" name="total">
            </td>
        </tr>
    </script>
<?php
$loadProduct = \yii\helpers\Url::toRoute(['ajax/load-product']);
$totalUpdate = \yii\helpers\Url::toRoute(['ajax/update-total']);
$js = <<<JS
    
    $("body").on('click','.removeItem',function() {
        let qty = $(this).closest("tr").find("input[type='number']").val();
        swal.fire({
            title : 'Cảnh báo',
            icon : "error",
            text  : 'Loại bỏ sản phẩm này?',
            showCancelButton : true
        }).then(val =>{
            if(val.value){
                $(this).closest(".form-group").remove();
                let _sku = $(this).data("sku");
                window.Skulist = removeArray(Skulist,_sku)
                updateTotal(_sku,qty, Action.DELTE)
            }
        });
        
    })
    $("body").on('focusin',"input[type='number']", function(){
        $(this).attr("data-prev", $(this).val());
    });
    $("body").on("change","input[type='number']",function(e) {
        let _sku = $(this).data("sku");
         var _prev = $(this).data('prev');
         var _val = $(this).val();
        
    });
     $("body").on('click','#addProduct',function() {
        
         let _sku = $(this).closest(".form-group").find("select > option:selected").val();
        $.ajax({
            url : '$loadProduct',
            cache : false,
            type :'POST',
            data : {sku : _sku},
            success : function(res) {
                    if(Skulist.includes(_sku)){
                       toastr.warning("Sản phẩm đã tồn tại");
                        return false;
                    }else{
                        $("#resultItemProduct").prepend(compileTemplate('template-item-product',res))
                        Skulist.push(res.sku)
                    }
                    updateTotal(_sku,1, Action.ADD)
            }
        })
    })
     
    $(document).on("beforeSubmit","#formOrder",function(e) {
        e.preventDefault();
        let form = new FormData(document.getElementById("formOrder"))
        
            $.ajax({
                        url : $(this).attr("action"),
                        contentType: false,
                        processData: false,
                        type : 'POST',
                        data : form,
                        success : function(res) {
                            if(res.success){
                                toastr.success("Tạo đơn hàng thành công!")
                                $("#takeOrder").modal("hide")
                                $("#formOrder").reset() 
                            }
                        }
            });
          
        return false;
    })
    
    function updateTotal(sku, _val = 1, action = Action.DELTE) {
        
         $.ajax({
            url : '$totalUpdate',
            type : 'POST',
            cache: false,
            data : {sku : sku, qty : _val},
            success : function(res){
                Total = caculate(Total,res,action)
                let html =  compileTemplate("total-template",Total);
                        $("#totalResult").html(html) 
            }
         })
    }
  
JS;

$this->registerJs($js);