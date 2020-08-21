<?php

use backend\models\ContactsModel;
use backend\models\ContactsAssignment;
use yii\helpers\ArrayHelper;
use common\helper\Helper;
use backend\models\UserModel;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Contacts Models';
$this->params['breadcrumbs'][] = $this->title;
?>
    <ul class="nav nav-tabs tabs-line">
        <li class="nav-item">
            <a class="nav-link active" href="#call_firts" data-toggle="tab">
                <i class="ti-bar-chart"></i> Lần gọi 1</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#call_second" data-toggle="tab">
                <i class="ti-bar-chart"></i> Lần gọi 2</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="call_firts">
            <div class="row">
                <div class="col-md-12">
                    <?= $this->render('_collapse_order', ['model' => $order]) ?>
                </div>

                <div class="col-md-4">
                    <?php Pjax::begin([
                        'id' => 'pjax-info'
                    ]) ?>
                    <div class="ibox">
                        <div class="ibox-head">
                            <h2 style="cursor: pointer" data-toggle="tooltip" title="Click 2 lần để coppy"
                                class="text-success ibox-title"><i
                                        class="fa fa-phone"></i> <?= $info ? "<span ondblclick=\"coppy(this)\">0$info->phone</span>" : "Chưa có liên hệ mới" ?>
                            </h2>
                        </div>

                        <div class="ibox-body">
                            <table class="table">
                                <tbody>
                                <?php
                                if ($info) {
                                    ?>
                                    <tr>
                                        <td>Trạng thái hiện tại</td>
                                        <td><?= ContactsAssignment::label(isset($info->assignment) ? $info->assignment->status : "") ?></td>
                                    </tr>
                                    <tr>
                                        <td>Khách hàng</td>
                                        <td><?= $info->name ?></td>
                                    </tr>
                                    <tr>
                                        <td>Địa chỉ</td>
                                        <td><?= $info->address ?></td>
                                    </tr>
                                    <tr>
                                        <td>Zipcode</td>
                                        <td><?= $info->zipcode ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <?php
                                if ($time = UserModel::hasCallback()) {
                                    ?>
                                    <tfoot>
                                    <tr>
                                        <td>Ghi chú gọi lại : <br>
                                            <strong class="text-danger"><?= $time['phone'] ?></strong>
                                        </td>
                                        <td>
                                            <strong>Thời gian tạo: <br>
                                                <span class="text-warning"><?= $time['created'] ?></span>
                                            </strong><br>
                                            <strong>Lần xử lý cuối: <br>
                                                <span class="text-warning"><?= $time['last_called'] ?></span>
                                            </strong><br>
                                            <strong>Lần gọi tiếp theo: <br>
                                                <span class="text-danger"><?= $time['time'] ?></span>
                                            </strong>
                                        </td>
                                    </tr>
                                    </tfoot>
                                <?php } ?>
                            </table>
                        </div>

                    </div>
                    <div class="ibox">
                        <div class="ibox-head">
                            <h2 class="ibox-title">Tài khoản</h2>
                        </div>
                        <div class="ibox-body">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>Tài khoản:</td>
                                    <td><?= $user->username ?></td>
                                </tr>
                                <tr>
                                    <td>SĐT đã hoàn thành hôm nay:</td>
                                    <td><?= UserModel::completed() . " /" . $user->phone_of_day ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php Pjax::end() ?>
                </div>

                <div class="col-md-8">
                    <div class="ibox">
                        <div class="ibox-body">
                            <ul class="nav nav-tabs tabs-line">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#wating" data-toggle="tab"><i
                                                class="ti-bar-chart"></i> Chờ
                                        xử lý (<?= $dataProvider->getCount() ?>)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#callback" data-toggle="tab">
                                        <i class="ti-time"></i> Gọi lại (<?= $callbackProvider->getCount() ?>)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#failure" data-toggle="tab"><i class="ti-settings"></i>
                                        Thất
                                        bại (<?= $failureProvider->getCount() ?>)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#success" data-toggle="tab"><i
                                                class="ti-announcement"></i> Thành
                                        công (<?= $successProvider->getCount() ?>)</a>
                                </li>

                            </ul>
                            <div class="tab-content">


                                <?= $this->render('_search', ['model' => $searchModel]) ?>
                                <div class="tab-pane fade show active" id="wating">
                                    <div class="d-flex justify-content-between">
                                        <div class="">
                                            <?php if (Helper::userRole(UserModel::_ADMIN)) {
                                                ?>
                                                <a id="changeStatus" class="btn btn-sm btn-info" href="#">Thay đổi trạng
                                                    thái</a>
                                            <?php } ?>
                                            <button id="createOrder" class="createOrder btn btn-sm btn-info">Tạo đơn
                                                hàng
                                            </button>
                                        </div>
                                        <a class="nav-link" href="#filter" data-toggle="collapse">
                                            <i class="ti-filter"></i> Tìm kiếm</a>
                                    </div>
                                    <?= $this->render('_tab_wait', ['dataProvider' => $dataProvider]) ?>
                                </div>
                                <div class="tab-pane fade" id="callback">
                                    <div class="mb-2">
                                        <?php if (Helper::userRole(UserModel::_ADMIN)) {
                                            ?>
                                            <a id="changeStatus" class="btn btn-sm btn-info" href="#">Thay đổi trạng
                                                thái</a>
                                        <?php } ?>
                                        <button id="createOrder" class="btn btn-sm btn-info">Tạo đơn hàng</button>
                                    </div>

                                    <?= $this->render('_tab_callback', ['dataProvider' => $callbackProvider]) ?>
                                </div>
                                <div class="tab-pane fade" id="failure">
                                    <?= $this->render('_tab_fail', ['dataProvider' => $failureProvider]) ?>
                                </div>
                                <div class="tab-pane fade" id="success">
                                    <?= $this->render('_tab_done', ['dataProvider' => $successProvider]) ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <?= $this->render('_contact_histories', [
                        'dataProvider' => $currentHistories,
                        'title' => $info ? "Lịch sử số " . $info->phone : "Lịch sử hiện tại",
                        'id' => 'current'
                    ]) ?>

                    <?= $this->render('_contact_histories', ['dataProvider' => $contactHistories]) ?>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="call_second">
            <?= $this->render('_call_2', [
                'dataProvider' => $_dataProvider,
                'failureProvider' => $_failureProvider,
                'successProvider' => $_successProvider,
                'order' => $order,
                'info' => $_info,
                'user' => $user,
                'searchModel' => $searchModel,
                'callbackProvider' => $_callbackProvider,
                'currentHistories' => $_currentHistories,
                'contactHistories' => $contactHistories,
            ]) ?>
        </div>

    </div>

<?php
$route = Url::toRoute(Yii::$app->controller->getRoute());
$loadProduct = Url::toRoute(['ajax/load-product-select']);
$skuURL = Url::toRoute(['ajax/load-sku']);
$js = <<<JS
   
    $("document").ready(function() {
        window.ORDER = {
            skus : [],
            option : "",
            cate : null,
            formInfos : [],
            products : [],
            billings : [],
            total : 0,
            subTotal : 0,
            shipping : 0
        }
        
        $(".createOrder").click(function() {
            var keys = $('.grid-view').yiiGridView('getSelectedRows');
            if(keys.length <= 0){
                swal.fire({
                    title : "Thông báo",
                    text : "Để lên đơn hàng hãy chọn 1 liên hệ",
                    icon : "error",
                });
                return;
            }
            if(keys.length > 1){
                 swal.fire({
                    title : "Thông báo",
                    text : "Chỉ chọn duy nhất một liên hệ để tạo đơn hàng",
                    icon : "info",
                });
                 return;
            }
           $("#collapse-order").collapse('show');
            
           $("html, body").animate({ scrollTop: 0 }, "slow");
           
           restOrder();
           loadProducts(keys).then(() => loadSku(getSelectedColum()))
           .then(() => {
                __findOrderForm(ORDER.option, ORDER.cate);
           })
        });
        
       $("#collapse-order").on("hidden.bs.collapse", function() {
            if(ORDER.billings.length > 0){
                _removeImage();
            }
       });
       
       async function loadProducts(keys) {
            if(keys.length > 0){
              await  $.ajax({    
                    url : "$loadProduct",
                    type : "POST",
                    data : {keys : keys},
                    cache : false,
                    success: function(res) {
                        const { zipcode , country } = res.customer.info;
                       
                        detectLocalCity(zipcode, country)
                            .then(data => { 
                                const {city , district } = data;
                                res.customer.info.city = city;
                                res.customer.info.district = district;
                            }).then(() => {
                                    let html =  compileTemplate("template-product",res.customer);
                                    $("#resultInfo").html(html);
                                     setORDER(res);
                            }).catch(error => {
                                 let html =  compileTemplate("template-product",res.customer);
                                    $("#resultInfo").html(html);
                                     setORDER(res);
                            })
                    
                    }
                })
            }
        }  
     
        function loadSku(_keys) {
            $.ajax({
            url : '$skuURL',
            data : {keys : _keys},
            type : 'POST',
            cache : false,
            success : function(res) {
               $("#resultProduct").html(compileTemplate('template-sku',res))
               initSelect2();
            }
            })
        }
        function getSelectedColum(grid_id = 'grid-view') {
            let cate = [];
            let cat = $("."+grid_id).find("tbody").find("input[type='checkbox']:checked");
            cat.each(function() {
                let _id= $(this).data("cate");
                cate.push(_id)
            })
          return   Array.from(new Set(cate))
        }
        
        function setORDER(res) {
            
            let _products = res.product;
            _products.map( item  => {
                if(ORDER.skus.includes(item.sku)){
                    return 0;
                };
                
                ORDER.option = item.selected;
                ORDER.cate = item.category_id;
            
                ORDER.skus.push(item.sku);
               __addItemProduct(item);
            });
            renderProduct();
        }
        
       
        
        $("body").on("change",".currentPhoneSelect",function(res) {
            let _current = $(this).val();
     
            if(_current == "null" || _current == null){
                window.location.replace("$route");
                return false;
            }else{
                window.location.replace("$route" + "?phoneview=" + _current);
            }
        });
    });
JS;

$this->registerJs($js);