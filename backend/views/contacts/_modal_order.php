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
        <input type="hidden" value="<?= Yii::$app->user->getId() ?>" name="user_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tạo đơn hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-info  m-t-10"><i class="fa fa-bar-chart"></i> Thông tin khách hàng
                                </h5>

                            </div>
                            <div id="resultInfo" class="col-12">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
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
                                    <td width="20%" class="text-right">Tổng cộng</td>
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
                <small class="text-danger">Các thông tin (*) là bắt buộc</small>
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
                    <label>Tên khách hàng <span class="text-danger">(*)</span></label>
                    <input required name="customer_name" value="{{this.name}}" class="form-control">
                    <input type="hidden" name="contact_id" value="{{this.id}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Số điện toại <span class="text-danger">(*)</span></label>
                    <input required name="customer_phone" value="{{this.phone}}" class="form-control">
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
                    <label>Địa chỉ <span class="text-danger">(*)</span></label>
                    <input required name="address" value="{{this.address}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Quận/huyện <span class="text-danger"></span></label>
                    <input  name="district" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Thành phố <span class="text-danger"></span></label>
                    <input  name="city" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Zipcode <span class="text-danger">(*)</span></label>
                    <input required name="zipcode" value="{{this.zipcode}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Quốc gia <span class="text-danger"></span></label>
                    <select class="form-control select2" name="country">
                        <option value="">Chọn quốc gia</option>
                    </select>
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

            <td>{{product.name}}<br>
                <small>{{product.category.name}}|{{product.sku}}</small>
                <input type="hidden" value="{{product.sku}}" name="product[{{product.sku}}][product_sku]">
            </td>
            <td>
                {{#if (hasArray customer.option product.option)}}
                <select class="form-control" name="product[{{product.sku}}][product_option]">
                    {{#each product.option}}
                    <option {{selected this ..
                    /product.selected}} value="{{this}}">{{this}}</option>
                    {{/each}}
                </select>
                {{else}}
                <select class="form-control" name="product[{{product.sku}}][product_option]">
                    <option value="{{customer.option}}">{{customer.option}}</option>
                    {{#each product.option}}
                    <option {{selected this ..
                    /product.selected}} value="{{this}}">{{this}}</option>
                    {{/each}}
                </select>
                {{/if}}
            </td>

            <td class="text-right">
                <input data-sku="{{product.sku}}" value="{{ product.regular_price}}"
                       name="product[{{product.sku}}][price]" type="text"
                       class="money form-control">
            </td>
            <td>
                <button data-sku="{{product.sku}}" type="button" class="removeItem btn btn-xs btn-danger">xoá</button>
            </td>
        </tr>

    </script>
    <script type="text/x-hanldebars-template" id="total-template">
        <tr>
            <td colspan="2"><strong>Tổng đơn</strong></td>
            <td class="text-left">
                <strong>{{money this.total}}</strong>
                <input type="hidden" value="{{total}}" name="total">
            </td>
            <td></td>
        </tr>
    </script>
<?php
$loadProduct = \yii\helpers\Url::toRoute(['ajax/load-product']);
$totalUpdate = \yii\helpers\Url::toRoute(['ajax/update-total']);
$js = <<<JS
    $("body").on('click','.removeItem',function() {
        
        swal.fire({
            title : 'Cảnh báo',
            icon : "error",
            text  : 'Loại bỏ sản phẩm này?',
            showCancelButton : true
        }).then(val =>{
            if(val.value){
                $(this).closest(".form-group").remove();
                let _sku = $(this).data("sku");
                if(ORDER.skus.includes(_sku)){
                  ORDER.skus = ORDER.skus.filter(item => item !== _sku)
                  updateTotal(Action.DELTE, _sku) 
                }
            }
        });
        
    })

     $("body").on('click','#addProduct',function() {
         let _sku = $(this).closest(".form-group").find("select > option:selected").val();
        $.ajax({
            url : '$loadProduct',
            cache : false,
            type :'POST',
            data : {sku : _sku},
            success : function(res) {
                    if(ORDER.skus.includes(_sku)){
                        toastr.warning("Đã tồn tại "+_sku+" trong đơn hàng!");
                        return false;
                    }else{
                        ORDER.skus.push(_sku)
                        let _cach_item = {
                            qty : 1,
                            sku : _sku,
                            price : res.product.regular_price
                        }
                        ORDER.products.push(_cach_item)
                        updateTotal(Action.ADD, res.product.regular_price)
                        $("#resultItemProduct").prepend(compileTemplate('template-item-product',res))
                       
                    }
            }
        })
    })
     
    $(document).on("beforeSubmit","#formOrder",function(e) {
        e.preventDefault();
        let form = new FormData(document.getElementById("formOrder"))
        var keys = $('.grid-view').yiiGridView('getSelectedRows');
            form.append("contact_id", keys);
            
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
                                  window.location.reload();
                            }
                        }
            });
          restOrder();
        return false;
    })
    $("body").on("change",".qty-input",function() {
        let _qty = $(this).val()
        let _sku = $(this).data("sku")
        changeQty(_sku,_qty)
         initTotal(ORDER)
    }) 
    $("body").on("change",".money",function() {
        let _price = $(this).val()
        let _sku = $(this).data("sku")
        changePrice(_sku,_price)
          initTotal(ORDER)
    })
        function updateTotal(action = Action.ADD, value) {
        switch (action) {
          case "add":
                ORDER.total = parseFloat(ORDER.total) + parseFloat(value)
              break;
              case "del":
               ORDER.total = reloadProduct(value)
                  break;
               default:
                   break;
        }
        initTotal(ORDER)
    }
    
    function initTotal(val = window.ORDER) {
           let html =  compileTemplate("total-template",val);
           $("#totalResult").html(html) 
        }
        function reloadProduct(_sku) {
            let products = ORDER.products
            let _removed = products.find(item => item.sku == _sku)
            let _price = _removed.price * _removed.qty;
                ORDER.total = ORDER.total - _price
                ORDER.products = products.filter(item => item.sku !== _sku)
                return ORDER.total
        }
      
        
        function changePrice(_sku,_price) {
            let products = ORDER.products
            let _changed = products.find(item => item.sku == _sku)
            let _old_price = _changed.price * _changed.qty
            let _new_price  = _price * _changed.qty
              let _new = {
                qty : _changed.qty,
                sku :_sku,
                price : _price
            }
            ORDER.products = products.filter(item => item.sku !== _sku)
            ORDER.products.push(_new)
            ORDER.total = ORDER.total - _old_price + _new_price
        }
        
        
JS;

$this->registerJs($js);