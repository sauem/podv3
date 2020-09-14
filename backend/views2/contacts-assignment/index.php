<?php
$this->title = "Quản lý khách hàng";

use common\helper\Helper;
use yii\helpers\Url;

?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Khách hàng chờ xử lý</h4>
                    <div class="toolbar">
                        <div class="btn-group">
                            <?php if (Helper::isAdmin()) { ?>
                                <button type="button" class="btn btn-xs btn-info approvePhone"><i
                                            class="fe-bar-chart"></i> Phân bổ
                                </button>
                                <button data-backdrop="static"
                                        data-keyboard="false"
                                        data-remote="<?= \yii\helpers\Url::toRoute(['contacts-assignment/import']) ?>"
                                        data-toggle="modal"
                                        data-target="#remote-import"
                                        type="button" class="btn btn-xs btn-warning">
                                    <i class="fe-download-cloud"></i> Nhập liên hệ
                                </button>
                                <button data-backdrop="static" data-keyboard="false"
                                        data-remote="<?= \yii\helpers\Url::toRoute(['contacts-log/import']) ?>"
                                        data-toggle="modal"
                                        data-target="#logs-import"
                                        type="button" class="btn btn-xs btn-success"><i class="fe-download-cloud"></i>
                                    Nhập lịch sử
                                </button>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?= $this->render('tab/_tab_waiting', ['dataProvider' => $pendingProvider, 'searchModel' => $searchModel]) ?>
                </div>
            </div>
        </div>
    </div>
    <!--MODAL-->

    <?= $this->render("modal/_contact_modal") ?>
    <?= $this->render("modal/_log_modal") ?>
    <?= $this->render("modal/_modal_approve") ?>
    <?= $this->render("modal/_edit_row_modal")?>
<?php
$js = <<<JS
    initRemote("remote-import");
    initRemote("logs-import");
    window.PHONES = [];
    $(".approvePhone").click(function(e) {
        var keys = $('.grid-view').yiiGridView('getSelectedRows');
            if(keys.length <= 0){
                swal.fire({
                    title : "Thông báo",
                    text : "Để lên đơn hàng hãy chọn liên hệ",
                    icon : "error",
                });
                return;
            }
        $("#modalApprove").modal("show");
            window.PHONES = [];
         let _phones =  $('.grid-view').find("input[type='checkbox']");
         _phones.each(function() {
                let _phone = $(this).data("phone");
                let _country = $(this).data("country");
                if(typeof _phone !== "undefined" && $(this).is(":checked")){
                   if(!PHONES.includes(_phone)){
                        PHONES.push({
                            phone : _phone,
                            country : _country
                        });
                   }
                }
         });
          $("#resultPhone").html(compileTemplate("phone-template", window.PHONES))
    });
    $(".autoScan").click(function() {
         swal.fire({
            title : 'Xin chờ....',
            onBeforeOpen: function() {
                swal.showLoading();
                 $.ajax({
                    url : config.autoScan,
                    type: 'POST',
                    data : {},
                    cache : false,
                    success : function(res) {
                      if(res == "success"){
                           swal.hideLoading();
                           swal.fire("Thành công!","Đã cập nhật quản lý liên hệ","success")
                           .then(() => {
                               __reloadData();  
                           })
                      }
                    }
                  })
            }
         });
    })
   
JS;
$this->registerJs($js);