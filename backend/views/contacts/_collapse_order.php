<?php

use kartik\form\ActiveForm;
use yii\helpers\Url;

?>
    <div id="collapse-order" class="collapse">
        <div class="ibox">
            <div class="ibox-head">
                <div class="col-12 text-right   ">
                    <small class="text-danger">Các thông tin (*) là bắt buộc</small>
                    <button type="button" class="btn btn-secondary" data-target="#collapse-order"
                            data-toggle="collapse">Hủy
                    </button>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </div>
            </div>
            <div class="ibox-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'formOrder',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'action' => Url::toRoute(['orders/create'])
                ]) ?>

                <input type="hidden" value="<?= Yii::$app->user->getId() ?>" name="user_id">
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
                    <div class="col-12 text-right   ">
                        <small class="text-danger">Các thông tin (*) là bắt buộc</small>
                        <button type="button" class="btn btn-secondary" data-target="#collapse-order"
                                data-toggle="collapse">Hủy
                        </button>
                        <button type="submit" class="btn btn-success">Lưu</button>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>

    <script type="text/x-handlebars-template" id="template-product">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tên khách hàng <span class="text-danger">(*)</span></label>
                    <input required name="customer_name" value="{{this.info.name}}" class="form-control">
                    <input type="hidden" name="contact_id" value="{{this.ids}}" class="form-control">
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
                    <input name="customer_email" value="{{this.info.email}}" class="form-control">
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
                    <input name="district" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Thành phố <span class="text-danger"></span></label>
                    <input name="city" class="form-control">
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
                    </select>
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
                <input class="form-control" name=name="product[{{this.sku}}][product_option]" value="{{this.selected}}">
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

$loadProduct = Url::toRoute(['ajax/load-product']);
$totalUpdate = Url::toRoute(['ajax/update-total']);
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
      let _val = $(this).val();
      __changeProductPrice(_sku,_val);
    });


    $(document).on("beforeSubmit", "#formOrder",function(res) {
      res.preventDefault();
        let _formData = new FormData($(this)[0]);
        let _action = $(this).attr("action");
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
                    __reloadData("$currentPage");
                    return;
                }
                toastr.warning(res);
           }
        })
      return false;
    })
JS;
$this->registerJs($js);