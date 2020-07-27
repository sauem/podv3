<?php

use backend\models\ContactsModel;
use backend\models\ContactsAssignment;
use yii\helpers\ArrayHelper;
use common\helper\Helper;
use backend\models\UserModel;

$this->title = 'Contacts Models';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Thông tin : <i class="fa fa-phone">
                        </i> <?= $info ? $info->phone : "Chưa có liên hệ mới" ?>
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
                        if ($time = \backend\models\UserModel::hasCallback()) {
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
                                    <strong>Lần gọi cuối: <br>
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
                            <td><?= \backend\models\UserModel::completed() . " /" . $user->phone_of_day ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="ibox">
                <div class="ibox-body">
                    <ul class="nav nav-tabs tabs-line">
                        <li class="nav-item">
                            <a class="nav-link active" href="#wating" data-toggle="tab"><i class="ti-bar-chart"></i> Chờ
                                xử lý</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#callback" data-toggle="tab">
                                <i class="ti-time"></i> Gọi lại</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#failure" data-toggle="tab"><i class="ti-settings"></i> Thất
                                bại</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#success" data-toggle="tab"><i class="ti-announcement"></i> Thành
                                công</a>
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
                                    <button id="createOrder" class="btn btn-sm btn-info">Tạo đơn hàng</button>
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
            <?= $this->render('_histories', ['dataProvider' => $histories]) ?>
        </div>
    </div>
<?= $this->render('_modal_remote') ?>

<?= $this->render('_modal', ['model' => $modelNote]) ?>
<?= $this->render('_modal_order', ['model' => $order]) ?>

<?php
$route = \yii\helpers\Url::toRoute(Yii::$app->controller->getRoute());
$loadProduct = \yii\helpers\Url::toRoute(['ajax/load-product-select']);
$skuURL = \yii\helpers\Url::toRoute(['ajax/load-sku']);
$js = <<<JS
   
    $("document").ready(function() {
        window.ORDER = {
            skus : [],
            products : [],
            total : 0
        }
        $("#createOrder").click(function() {
            var keys = $('.grid-view').yiiGridView('getSelectedRows');
            if(keys.length <= 0){
                swal.fire({
                    title : "Thông báo",
                    text : "Để lên đơn hàng hãy chọn liên hệ",
                    icon : "error",
                });
                return;
            }
           $("#takeOrder").modal()
            getCountry("select[name='country']");
           $('#takeOrder').on('shown.bs.modal', function () {
               loadProducts(keys);
               loadSku(getSelectedColum());
               getCountry("select[name='country']");
            })
        });
       
        function loadProducts(keys) {
            if(keys.length > 0){
                $.ajax({    
                    url : "$loadProduct",
                    type : "POST",
                    data : {keys : keys},
                    cache : false,
                    success: function(res) {
                        let html =  compileTemplate("template-product",res.customer);
                        $("#resultInfo").html(html) 
                           let _same = 1;
                           
                           res.product.map(  item  => {
                                    item.qty  = _same
                                  let _set_data = {
                                      customer : res.customer,
                                      product : item
                                  }
                                  let _cacheItem = {
                                        qty : _same,
                                        sku : item.sku,
                                        price : item.regular_price
                                  }
                                  if(ORDER.skus.includes(item.sku)){
                                         _same = _same + 1
                                     let vm =  ORDER.products;
                                     vm.map((_item,index) => {
                                          if(item.sku ==  _item.sku){
                                              vm[index].qty =  _same
                                          }
                                      });
                                  }else{
                                      ORDER.skus.push(item.sku)
                                        ORDER.products.push(_cacheItem)
                                        ORDER.total = res.total
                                        let html = compileTemplate("template-item-product",_set_data)
                                        $("#resultItemProduct").append(html)
                                  }
                         })
                      initTotal()
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
    })
    
JS;

$this->registerJs($js);