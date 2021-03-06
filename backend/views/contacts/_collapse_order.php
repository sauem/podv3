<?php

use kartik\form\ActiveForm;
use yii\helpers\Url;

?>
<?php $form = ActiveForm::begin([
    'id' => 'formOrder',
    'options' => [
        'enctype' => 'multipart/form-data'
    ],
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'action' => Url::toRoute(['orders/create'])
]) ?>
    <div id="collapse-order" class="collapse">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title"><i class="fa fa-shopping-cart"></i> Tạo đơn hàng</h2>
                <div class="text-right   ">
                    <small class="text-danger">Các thông tin (*) là bắt buộc</small>
                    <button type="button" class="btn btn-secondary" data-target="#collapse-order"
                            data-toggle="collapse">Hủy
                    </button>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </div>
            </div>
            <div class="ibox-body">
                <input type="hidden" value="<?= Yii::$app->user->getId() ?>" name="user_id">
                <div class="row">

                    <div class="col-md-5">
                        <div class="row">
                            <div id="resultInfo" class="col-12">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between">
                            <h5 class="ibox-title"><i class="fa fa-shopping-bag"></i> Sản phẩm đặt mua</h5>
                            <div id="resultProduct">
                            </div>
                        </div>
                        <div id="resultFormInfo"></div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <td width="30%">Sản phẩm</td>
                                <td width="20%">Số lượng</td>
                                <td width="20%" class="text-left">Tổng cộng</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody id="resultItemProduct">

                            </tbody>
                            <tfoot id="totalResult">

                            </tfoot>

                        </table>
                    </div>
                    <div class="col-12 text-right   ">
                        <small class="text-danger">Các thông tin (*) là bắt buộc</small>
                        <button type="button" class="btn btn-secondary" data-target="#collapse-order"
                                data-toggle="collapse">Hủy
                        </button>
                        <button type="submit" class="btn btn-success">Lưu</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?= $this->render('_modal_view_info') ?>
<?php ActiveForm::end() ?>
    <script type="text/x-handlebars-template" id="template-product">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tên khách hàng <span class="text-danger">(*)</span></label>
                    <input required name="customer_name" value="{{this.info.name}}" class="form-control">
                    <input type="hidden" name="contact_id" value="{{this.ids}}" class="form-control">
                    <input type="hidden" name="code" value="{{this.info.code}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Số điện toại <span class="text-danger">(*)</span></label>
                    <input required name="customer_phone" value="{{this.info.phone}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="customer_email" value="{{this.info.email}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Địa chỉ <span class="text-danger">(*)</span> <small><a data-key="{{this.info.id}}" data-pjax="0" class="changeAddessDefault" href="javasript:;">Đổi địa chỉ</a></small></label>
                    <input required name="address" value="{{this.info.address}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Quận/huyện <span class="text-danger"></span></label>
                    <input value="{{this.info.district}}" name="district" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Thành phố <span class="text-danger"></span> <a data-key="{{this.info.id}}" data-pjax="0" class="autoUpdateCity" href="javasript:;">Cập nhật</a></label>
                    <input value="{{this.info.city}}" name="city" class="form-control">
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
                        <option value="{{this.id}}">{{this.name}}</option>
                        {{/each}}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phí vận chuyển </label>
                    <input required min="0" type="number" name="shipping_price" value="0" class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group bill-image" data-toggle="tooltip"
                     title="Tiệp hình ảnh .jpg,png,jpeg hoặc file pdf">
                    <label><i class="fa fa-cloud-upload"></i> Chọn hóa đơn chuyển khoản <br>
                    </label>
                    <input type="file" name="bill_transfer[]" multiple>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Ghi chú đơn hàng</label>
                    <textarea name="order_note" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Ghi chú nhà cung cấp</label>
                    <textarea name="vendor_note" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="text-warning ui-checkbox ui-checkbox-success">
                        <input checked type="checkbox" name="default_info">
                        <span class="input-span"></span>
                        Tạo thông tin đơn hàng mặc định <br>(*thông tin sau sẽ ghi đè thông tin trước)
                    </label>
                </div>
            </div>
        </div>
    </script>
    <script type="text/x-handlebars-template" id="template-sku">
        <div class="form-group btn-group">
            <select class="form-control select2">
                {{#each this}}
                <option value="{{this.sku}}">{{this.sku}} - {{this.name}}</option>
                {{/each}}
            </select>
            <button type="button" id="addProduct" class="btn btn-sm btn-success">Thêm sản phẩm</button>
        </div>
    </script>
    <script type="text/x-handlebars-template" id="template-item-product">

        <tr class="form-group">

            <td>{{this.name}}<br>
                <small>{{this.category}}|{{this.sku}}</small>
                <input type="hidden" value="{{this.sku}}" name="product[{{this.sku}}][product_sku]">
            </td>
            <td>
                <input name="product[{{this.sku}}][qty]" data-sku="{{this.sku}}" value="1" min="1" type="number"
                       class="form-control">
            </td>
            <td class="text-right">
                <input data-sku="{{this.sku}}" value="{{ this.price}}"
                       name="product[{{this.sku}}][price]" type="number"
                       class="money form-control">
            </td>
            <td>
                <button data-sku="{{this.sku}}" type="button" class="removeItem btn btn-xs btn-danger">xoá</button>
            </td>
        </tr>

    </script>
    <script type="text/x-handlebars-template" id="total-template">

        <tr>
            <td colspan="2"><strong>Tổng cộng</strong></td>
            <td class="text-left">
                <strong><input class="maskMoneyTotal form-control" value="{{money this.subTotal}}"></strong>
                <input type="hidden" value="{{subTotal}}" name="sub_total">
            </td>
        </tr>

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

    <script type="text/x-handlebars-template" id="template-form-info">
        <p>{{msg}} <a class="btn btn-sm btn-warning" data-target="#modalViewFormInfo" data-toggle="modal">Xem</a></p>
    </script>
<?php

$loadProduct = Url::toRoute(['ajax/load-product']);
$totalUpdate = Url::toRoute(['ajax/update-total']);
$billtransfer = Url::toRoute(['ajax/upload-bill']);
$currentPage = Url::toRoute(Yii::$app->controller->getRoute());
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
                  ORDER.skus = ORDER.skus.filter(item => item !== _sku);
                  ORDER.products = ORDER.products.filter( pro => pro.sku !== _sku);
                  __reloadTotal();
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
                     toastr.warning("Sản phẩm " + _sku + " đã tồn tại trong đơn hàng!");
                     return;
                 };
                ORDER.skus.push(_sku);
                let _item =  __addItemProduct(res.product);
                $("#resultItemProduct").prepend(compileTemplate("template-item-product", _item));
            }
        });
    })
    
    $("body").on("change",".money",function() {
      let _sku = $(this).data("sku");
      let _val = parseFloat($(this).val());
      if(typeof _val !== "number" || !_val){
          toastr.warning("Giá trị nhập phải là số!");
          $(this).val(0);
          _val = 0;
      }
      __changeProductPrice(_sku,_val);
    });


    $(document).on("beforeSubmit", "#formOrder",function(res) {
      res.preventDefault();
        let _formData = new FormData($(this)[0]);
        let _action = $(this).attr("action");
        _formData.append("bills" , ORDER.billings);
        if(ORDER.products.length <= 0){
            swal.fire("Chú ý!","Đơn hàng chưa có sản phẩm nào!","warning");
            return false;
        }else if( parseInt(_formData.get("total")) <= 0){
             swal.fire("Chú ý!","Tổng giá đơn hàng > 0","warning");
            return false;
        }
        $.ajax({
           url : _action,
           type : "POST",
           processData : false,
           contentType :false,
           data : _formData,
           success : function(res) {
               
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
    $("body").on("change","input[name='shipping_price']",function() {
        let _val = parseFloat($(this).val());
             if(typeof _val !== "number" || !_val){
                  toastr.warning("Giá trị nhập phải là số!");
                  $(this).val(0);
                   ORDER.shipping = 0;
             }else{
                 ORDER.shipping =  _val;
             }
                 __reloadTotal();
    });
    
    
    $('#modalViewFormInfo').on('shown.bs.modal', function (e) {
        $("#resultApplyInfo").html(compileTemplate("template-view-info",ORDER.formInfosBase));
    });
JS;
$this->registerJs($js);