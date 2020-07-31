<?php

use yii\helpers\Url;
use kartik\form\ActiveForm;

?>

    <div class="modal fade" id="orderEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <?php $form = ActiveForm::begin([
                'id' => 'formOrder',
                'action' => Url::toRoute(['orders/update'])
            ]) ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa đơn hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="resultNote">
                    <div class="row">
                        <div class="col-md-5">
                            <div id="resultInfo"></div>
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between">
                                <h5 class="ibox-title"><i class="fa fa-shopping-bag"></i> Sản phẩm đặt mua</h5>
                                <div id="resultSelectSku">
                                </div>
                            </div>
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
                <div class="modal-footer">
                    <small class="text-danger">Các thông tin (*) là bắt buộc</small>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
    <script id="info-template" type="text/x-handlebars-template">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tên khách hàng <span class="text-danger">(*)</span></label>
                    <input required name="customer_name" value="{{this.info.customer_name}}" class="form-control">
                    <input required name="order_id" value="{{this.info.id}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Số điện toại <span class="text-danger">(*)</span></label>
                    <input required name="customer_phone" value="{{this.info.customer_phone}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email</label>
                    <input name="customer_email" value="{{this.info.customer_email}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Địa chỉ <span class="text-danger">(*)</span></label>
                    <input required name="address" value="{{this.info.address}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Quận/huyện <span class="text-danger"></span></label>
                    <input name="district" value="{{this.info.district}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Thành phố <span class="text-danger"></span></label>
                    <input name="city" value="{{this.info.city}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Zipcode <span class="text-danger">(*)</span></label>
                    <input required name="zipcode" value="{{this.info.zipcode}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Quốc gia <span class="text-danger"></span></label>
                    <select class="form-control select2" name="country">
                        <option value="">Chọn quốc gia</option>
                        {{#each this.countries}}
                        <option {{selected ..
                        /info.country this.code}} value="{{this.code}}">{{this.name}}</option>
                        {{/each}}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phương thức thanh toán <span class="text-danger">(*)</span></label>
                    <select required class="form-control" name="payment_method">
                        <option value="">Chọn PTTT...</option>
                        {{#each this.payment}}
                        <option {{selected ..
                        /info.payment_method this.id}} value="{{this.id}}">{{this.name}}</option>
                        {{/each}}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phí vận chuyển </label>
                    <input required min="0" type="number" name="shipping_price" value="{{this.info.shipping_price}}"
                           class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group bill-image" data-toggle="tooltip"
                     title="Tiệp hình ảnh .jpg,png,jpeg hoặc file pdf">
                    <label><i class="fa fa-cloud-upload"></i> Chọn hóa đơn chuyển khoản <br>
                    </label>
                    <input type="file" name="bill_transfer[]" multiple>
                </div>

                <div class="row">
                    {{#if this.info.billings}}
                    <div class="col-12">
                        <small data-toggle="collapse" data-target="#bill-view" class="text-danger float-right btn"><i
                                    class="fa fa-file-pdf-o"></i> Hiển thị hóa đơn</small>
                    </div>
                    <div class="col-12">
                        <div id="bill-view" class="collapse  mt-2">
                            <div class="row">
                                {{#each this.info.billings}}
                                <div class="col-md-6">
                                    <div class="bill-item ">
                                        <button data-key="{{../info.id}}" data-path="{{this.path}}" type="button"
                                                class="removeImage btn-xs btn-rounded rounded-circle"><i
                                                    class="fa fa-times"></i></button>
                                        <img src="{{asset this.path}}" class="img-fluid img-thumbnail rounded">
                                    </div>
                                </div>
                                {{/each}}
                            </div>
                        </div>
                    </div>
                    {{/if}}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Ghi chú đơn hàng</label>
                    <textarea name="order_note" class="form-control">{{this.info.order_note}}</textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Ghi chú nhà cung cấp</label>
                    <textarea name="vendor_note" class="form-control">{{this.info.vendor_note}}</textarea>
                </div>
            </div>
        </div>
    </script>

    <script id="product-template" type="text/x-handlebars-template">
        <tr class="form-group">

            <td>{{this.name}}<br>
                <small>{{this.category}}|{{this.sku}}</small>
                <input type="hidden" value="{{this.sku}}" name="product[{{this.sku}}][product_sku]">
            </td>
            <td>
                {{#if (hasArray selected this.option)}}
                <select name="product[{{this.sku}}][product_option]" class="form-control">
                    {{#each this.option}}
                    <option {{selected ..
                    /selected this}} value="{{this}}">{{this}}</option>
                    {{/each}}
                </select>
                {{ else }}
                <input class="form-control" name="product[{{this.sku}}][product_option]" value="{{this.selected}}">
                {{/if}}
            </td>
            <td class="text-right">
                <input data-sku="{{this.sku}}" value="{{ this.price}}"
                       name="product[{{this.sku}}][price]" type="text"
                       class="money form-control">
            </td>
            <td>
                <button data-sku="{{this.sku}}" type="button" class="removeItem btn btn-xs btn-danger">xoá</button>
            </td>
        </tr>
    </script>

    <script type="text/x-handlebars-template" id="sku-template">
        <div class="form-group">
            <select class="form-control select2">
                {{#each this}}
                <option value="{{this.sku}}">{{this.sku}} - {{this.name}}</option>
                {{/each}}
            </select>
            <button type="button" id="addProduct" class="btn btn-success">Thêm sản phẩm</button>
        </div>
    </script>

    <script type="text/x-hanldebars-template" id="total-template">
        <tr>
            <td colspan="2">Phí ship</td>
            <td><strong>{{money this.shipping}}</strong></td>
            <td></td>
        </tr>
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
$skuURL = Url::toRoute(['ajax/load-sku']);
$loadProduct = Url::toRoute(['ajax/load-product']);
$js = <<<JS
        window.ORDER = {
            skus : [],
            products : [],
            billings : [],
            total : 0,
            shipping : 0
        }
    $("#orderEdit").on("show.bs.modal",function(e) {
          let button = $(e.relatedTarget);
          let _key =  button.data("key");
          __loadData(_key);
          restOrder();
    });
    $("#orderEdit").on("shown.bs.modal",function() {
       let _val = $("select[name='payment_method']").val();
         switch (_val) {
                case "9999":
                    $(".bill-image").css({"display" : "block"});
                    $(".bill-image").find("input[type='file']").attr("required",true);
                    break;
                default:
                    $(".bill-image").css({"display" : "none"});
                    $(".bill-image").find("input[type='file']").attr("required",false);
                    break;
            }
    });
    $("#orderEdit").on("hidden.bs.modal",function(e) {
        $("#resultProduct").empty();
        $("#resultItemProduct").empty();
        $("#resutlTotal").empty();
        if(ORDER.billings.length > 0){
                _removeImage();
            }
        
       
    });
    $("body").on("click",".removeItem",function() {
      __removeItem(this);
    });
     $("body").on('click','#addProduct',function() {
        __addProduct(this); 
     });
     
      $("body").on("change",".money",function() {
        __moneyChange(this);
        });
      
    function __loadData(_key){
        $.ajax({
            url : config.orderData,
            cache : false,
            type : 'POST',
            data : { key : _key},
            success : function(res) {
                if(res.success){
                    __complieTemplate(res);
                }else{
                    toastr.warning(res.msg);
                }
            }
        })        
    }
    function __complieTemplate(res) {
        
        $("#resultInfo").html(compileTemplate("info-template",res.customer));
         ORDER.shipping = res.customer.info.shipping_price;
       
        let items = res.items;
        if(items.length > 0){
             $.each(items, function(index, item) {
                 
                 let _item =  __addItemProduct(item.product, item.price);
                 if(!ORDER.skus.includes(_item.sku)){
                     ORDER.skus.push(_item.sku);
                 }
                 $("#resultItemProduct").append(compileTemplate("product-template", _item));
            })
        }
        __loadSku();
    }
    
     function __loadSku() {
           $.ajax({
            url : '$skuURL',
            data : {},
            type : 'POST',
            cache : false,
            success : function(res) {
               $("#resultSelectSku").html(compileTemplate('sku-template',res))
               initSelect2();
            }
            })
        }
        function __removeItem(_this) {
          swal.fire({
                    title : 'Cảnh báo',
                    icon : "error",
                    text  : 'Loại bỏ sản phẩm này?',
                    showCancelButton : true
                }).then(val =>{
                    if(val.value){
                        $(_this).closest(".form-group").remove();
                        let _sku = $(_this).data("sku");
                        if(ORDER.skus.includes(_sku)){
                          ORDER.skus = ORDER.skus.filter(item => item !== _sku);
                          ORDER.products = ORDER.products.filter( pro => pro.sku !== _sku);
                          __reloadTotal();
                        }
                    }
                });
        }
        function __addProduct(_this) {
            let _sku = $(_this).closest(".form-group").find("select > option:selected").val();
            
                $.ajax({
                    url : '$loadProduct',
                    cache : false,
                    type :'POST',
                    data : {sku : _sku},
                    success : function(res) {
                        
                         if(ORDER.skus.includes(_sku)){
                             toastr.warning("Sản phẩm " + _sku + " đã tồn tại trong đơn hàng!");
                             return;
                         };
                        ORDER.skus.push(_sku);
                        let _item =  __addItemProduct(res.product);
                        $("#resultItemProduct").prepend(compileTemplate("product-template", _item));
                        __reloadTotal();
                    }
                });
        }
        
        
    function __moneyChange(_this) {
      let _sku = $(_this).data("sku");
      let _val = $(_this).val();
      __changeProductPrice(_sku,_val);
    }
      $("body").on("change","input[name='shipping_price']",function() {
        let _val = $(this).val();
        ORDER.shipping = typeof _val == "undefined" ? 0 : _val;
        __reloadTotal();
    });
    
    $(document).on("beforeSubmit", "#formOrder",function(res) {
      res.preventDefault();
        let _formData = new FormData($(this)[0]);
        let _action = $(this).attr("action");
        _formData.append("bills" , ORDER.billings);
        $.ajax({
           url : _action,
           type : "POST",
           processData : false,
           contentType :false,
           data : _formData,
           success : function(res) {
                console.log(res);
                return;
                if(res.success){
                    toastr.success("Tạo đơn hàng thành công!");
                    $("#collapse-order").collapse("hide");
                    restOrder();
                    __reloadData();
                    return;
                }
                toastr.warning(res);
           }
        })
      return false;
    })
    
    
JS;
$this->registerJs($js);
?>