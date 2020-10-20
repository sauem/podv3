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
        <div class="card">
            <div class="card-loading">
                <div class="spinner-grow text-success" role="status"></div>
            </div>
            <div class="card-body">
                <input type="hidden" value="<?= Yii::$app->user->getId() ?>" name="user_id">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title"><i class="fe-shopping-cart"></i> Tạo đơn hàng</h4>
                    <div class="text-right">
                        <small class="text-danger mr-4">Các thông tin (*) là bắt buộc</small>
                        <button type="button" class="btn btn-sm btn-secondary mr-1" data-target="#collapse-order"
                                data-toggle="collapse"><i class="fe-x"></i> Hủy
                        </button>
                        <button type="submit" class="btn btn-sm btn-success"><i class="fe-save"></i> Lưu</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-5">
                        <h4 class="card-title mb-1">
                            <i class="fe-user"></i>
                            Thông tin khách hàng
                        </h4>
                        <div class="row">
                            <div id="customer-info" class="col-12">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-block">
                            <h4 class="card-title mb-4">
                                <i class="fe-shopping-bag"></i> Sản phẩm đặt mua
                            </h4>
                            <div id="product-list">
                            </div>
                        </div>
                        <div id="order-example" class="mt-2"></div>
                        <table class="table mt-2 table-sm table-bordered table-hover">
                            <thead>
                            <tr>
                                <td width="30%">Sản phẩm</td>
                                <td width="20%">Số lượng</td>
                                <td width="20%" class="text-left">Tổng tiền</td>
                            </tr>
                            </thead>
                            <tbody id="product-contact">

                            </tbody>
                            <tfoot id="amount-order">

                            </tfoot>

                        </table>
                    </div>
                    <div class="col-12 text-right">
                        <small class="text-danger mr-4">Các thông tin (*) là bắt buộc</small>
                        <button type="button" class="btn btn-sm btn-secondary mr-1" data-target="#collapse-order"
                                data-toggle="collapse"><i class="fe-x"></i> Hủy
                        </button>
                        <button type="submit" class="btn btn-sm btn-success"><i class="fe-save"></i> Lưu</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php ActiveForm::end() ?>
    <script type="text/x-handlebars-template" id="customer-template">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tên khách hàng <span class="text-danger">(*)</span></label>
                    <input required name="customer_name" value="{{this.customer.name}}" class="form-control">
                    <input type="hidden" name="contact_id" value="{{this.customer.lead}}" class="form-control">
                    <input type="hidden" name="code" value="{{this.customer.code}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Số điện toại <span class="text-danger">(*)</span></label>
                    <input required name="customer_phone" value="{{this.customer.phone}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="customer_email" value="{{this.customer.email}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="d-flex justify-content-between">
                        <span>
                            Địa chỉ <span class="text-danger">(*)</span>
                        </span>
                    </label>
                    <input required name="address" value="{{this.customer.address}}" class="form-control">
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
                    <label class="d-flex justify-content-between">
                        <span>Thành phố</span>
                        <small class="float-right">
                            <a data-key="{{this.customer.lead}}"
                               data-pjax="0"
                               class="autoUpdateCity"
                               href="javascript:;">
                                <i class="fe-edit"></i> cập nhập
                            </a>
                        </small>
                    </label>
                    <input name="city" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Zipcode <span class="text-danger">(*)</span></label>
                    <input type="number" required name="zipcode" value="{{this.customer.zipcode}}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Quốc gia <span class="text-danger"></span></label>
                    <select class="form-control select2" name="country">
                        <option value="">Chọn quốc gia</option>
                        {{#each this.countries}}
                        <option {{selected ..
                        /customer.country this.code}} value="{{this.code}}">{{this.name}}</option>
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
                    <label>Ghi chú cho đơn vị vận chuyển</label>
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
    <!--    product-list-->
    <script type="text/x-handlebars-template" id="product-list-template">
        <div class="input-group">
            <div style="width: 50%">
                <select class="form-control select2">
                    {{#each this}}
                    <option value="{{this.sku}}">{{this.sku}} - {{this.name}}</option>
                    {{/each}}
                </select>
            </div>
            <div class="input-group-append">
                <button type="button" id="addProduct" class="btn btn-xs btn-outline-success">
                    <i class="fe-plus"></i> Thêm sản phẩm
                </button>
            </div>
        </div>
    </script>
    <!--    product-contact-->
    <script type="text/x-handlebars-template" id="product-contact-template">
        <tr class="form-group">
            <td>{{this.name}}<br>
                <small>{{this.category}}|{{this.sku}}</small>
                <input type="hidden" value="{{this.sku}}" name="product[{{this.sku}}][product_sku]">
            </td>
            <td>
                <input name="product[{{this.sku}}][qty]" data-sku="{{this.sku}}" value="1" min="1" type="number"
                       class="form-control">
            </td>
            <td class="text-right input-group">
                <input data-sku="{{this.sku}}" value="{{ this.price}}"
                       name="product[{{this.sku}}][price]" type="number"
                       class="money form-control">
                <div class="input-group-append">
                    <button data-sku="{{this.sku}}"
                            type="button"
                            class="removeItem btn btn-xs btn-danger">
                        <i class="fe-trash"></i>
                    </button>
                </div>
            </td>
        </tr>

    </script>
    <!--    amount-order-->
    <script type="text/x-handlebars-template" id="amount-order-template">
        <tr>
            <td colspan="2"><strong>Tổng tiền</strong></td>
            <td class="text-left">
                <div class="input-group">
                    <input class="maskMoneyTotal form-control" value="{{money this.subTotal}}">
                    <div class="input-group-append">
                        <span class="input-group-text">{{currency}}</span>
                    </div>
                </div>

                <input type="hidden" value="{{subTotal}}" name="sub_total">
            </td>
        </tr>
        <tr>
            <td colspan="2">Phí ship</td>
            <td><strong>{{money shipping}} {{currency}}</strong></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Tổng đơn</strong></td>
            <td class="text-left">
                <strong>{{money total}} {{currency}}</strong>
                <input type="hidden" value="{{total}}" name="total">
            </td>
        </tr>
    </script>
    <!--    oder-example-->
    <script type="text/x-handlebars-template" id="order-example-template">
        <table class="table table-hover table-bordered table-sm">
            <thead>
            <tr>
                <th>loại sản phẩm</th>
                <th>Doanh thu</th>
                <th width="35%">Nội dung</th>
                <th>Sản phẩm</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            {{#each this}}
            <tr>

                <td>{{category}}</td>
                <td>{{money revenue}}</td>
                <td>{{content}}</td>
                <td>
                    {{#each skus}}
                    <small>{{this}}</small><br>
                    {{/each}}
                </td>
                <td>
                    <button data-key="{{@index}}" type="button" class="applyInfo btn btn-sm btn-info">Áp dụng</button>
                </td>
            </tr>
            {{/each }}
            </tbody>
        </table>
    </script>
<?php

$loadProduct = Url::toRoute(['ajax/load-product']);
$totalUpdate = Url::toRoute(['ajax/update-total']);
$billtransfer = Url::toRoute(['ajax/upload-bill']);
$currentPage = Url::toRoute(Yii::$app->controller->getRoute());
$js = <<<JS


JS;
$this->registerJs($js);