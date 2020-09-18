<?php
$this->title = "Quản lý khách hàng";

use common\helper\Helper;
use yii\helpers\Url;

?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <ul class="nav nav-tabs nav-bordered tabs-line">
                    <li class="nav-item">
                        <a class="nav-link active" href="#assign" data-toggle="tab">
                            <i class="ti-bar-chart"></i>
                            Chờ xử lý
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#all" data-toggle="tab">
                            <i class="ti-time"></i> Tất cả liên hệ
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="assign">
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
                                                data-remote="<?= Url::toRoute(['contacts-assignment/import']) ?>"
                                                data-toggle="modal"
                                                data-target="#remote-import"
                                                type="button" class="btn btn-xs btn-warning">
                                            <i class="fe-download-cloud"></i> Nhập liên hệ
                                        </button>
                                        <button data-backdrop="static" data-keyboard="false"
                                                data-remote="<?= Url::toRoute(['contacts-log/import']) ?>"
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
                    <div class="tab-pane fade" id="all">
                        <?= $this->render('tab/_tab_all', ['dataProvider' => $allProvider, 'searchModel' => $searchModel]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--MODAL-->

    <?= $this->render("modal/_contact_modal") ?>
    <?= $this->render("modal/_log_modal") ?>
    <?= $this->render("modal/_modal_approve") ?>
    <?= $this->render("modal/_edit_row_modal")?>
    <?= $this->render("modal/_error_row_modal")?>
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
JS;
$this->registerJs($js);