<?php

use yii\helpers\Url;
use backend\models\UserModel;
use yii\helpers\Html;
use yii\widgets\Pjax;

$user = Yii::$app->user;
?>


<?php
Pjax::begin([
    'id' => 'pjax-info'
]) ?>

    <div class="card-box">
        <ul class="nav nav-tabs nav-bordered tabs-line">
            <li class="nav-item">
                <a class="nav-link active" href="#wating" data-toggle="tab">
                    <i class="ti-bar-chart"></i>
                    Lần gọi 1
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#callback" data-toggle="tab">
                    <i class="ti-time"></i> Lần gọi 2
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <?= $this->render("order_form") ?>
            <div class="tab-pane fade show active" id="wating">
                <?= $this->render("tab/first_call",
                    [
                        'dataProvider' => $dataProvider,
                        'callbackProvider' => $callbackProvider,
                        'failureProvider' => $failureProvider,
                        'successProvider' => $successProvider,
                        'currentHistories' => $currentHistories,
                        'user' => $user,
                        'info' => $info
                    ]) ?>

            </div>
            <div class="tab-pane fade" id="callback">
                <?= $this->render("tab/second_call",
                    [
                        'dataProvider' => $_dataProvider,
                        'callbackProvider' => $_callbackProvider,
                        'failureProvider' => $_failureProvider,
                        'successProvider' => $_successProvider,
                        'currentHistories' => $_currentHistories,
                        'user' => $user,
                        'info' => $_info
                    ]) ?>

            </div>
        </div>
    </div>
    <div class="card card-body">
        <h4 class="card-title">Lịch sử đơn hàng</h4>
        <?= $this->render('order_histories', [
            'dataProvider' => $histories,
            'id' => 'order'
        ]) ?>
    </div>

    <div class="card card-body">
        <h4 class="card-title">Lịch sử cuộc gọi</h4>
        <?= $this->render('contact_histories', [
            'dataProvider' => $contactHistories,
            'id' => 'contacthistory'
        ]) ?>
    </div>

<?php Pjax::end() ?>
<?php
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
            let key = $(this).data("key");
           $("#collapse-order").collapse('show');
           $("html, body").animate({ scrollTop: 0 }, "slow");
           restOrder();
           
            const {option, cate}  = ORDER;
            
            swal.fire({
            title : "Đang đọc dữ liệu...",
            onBeforeOpen : function() {
                swal.showLoading();
                loadProducts(key)
                .then( res => {
                    __findOrderForm(res.option, res.category);
                })
                .then(() => loadSku())
                .then(() => {
                    setTimeout(()=> swal.close(), 500)
                });
              }
           });
        });
        
       $("#collapse-order").on("hidden.bs.collapse", function() {
            $('.grid-view').find("input[type='checkbox']").attr("checked",false);
          
            if(ORDER.billings.length > 0){
                _removeImage();
            }
       });
    });
JS;
$this->registerJs($js);