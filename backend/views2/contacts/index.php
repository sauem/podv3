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
        <ul class="nav nav-tabs tabs-line">
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
                        'user' => $user,
                        'info' => $info
                    ]) ?>
            </div>
            <div class="tab-pane fade" id="callback">
                <h3>Lần gọi 2</h3>
            </div>
        </div>
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
            swal.fire({
            title : "Đang đọc dữ liệu...",
            onBeforeOpen : function() {
                swal.showLoading();
                loadProducts(key).then(() => loadSku())
                .then(() => {
                        __findOrderForm(ORDER.option, ORDER.cate);
                })
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