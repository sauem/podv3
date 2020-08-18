<?php

use common\helper\Component;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\export\ExportMenu;

$this->title = 'Contacts Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-12 text-right mb-2">
            <button class="btn btn-info autoScan"><i class="fa fa-print"></i> Tự động phân bổ
            </button>
            <button data-backdrop="static" data-keyboard="false"
                    data-remote="<?= \yii\helpers\Url::toRoute(['contacts-assignment/import']) ?>" data-toggle="modal"
                    data-target="#remote-import" class="btn btn-success">
                <i class="fa fa-file-excel-o"></i> Nhập liên hệ
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Liên hệ chờ xử lý/thất bại</h2>
                    <div class="ibox-tools">
                        <a href="javascript:;" class="approvePhone"><i class="fa fa-cogs"></i> Phân bổ</a>
                        <a data-toggle="collapse" href="#filter1"><i class="fa fa-filter"></i> Tìm kiếm</a>
                    </div>
                </div>
                <div class="ibox-body">
                    <?= $this->render('_tab_waiting', ['dataProvider' => $pendingProvider, 'searchModel' => $searchModel]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Hẹn gọi lại</h2>
                </div>
                <div class="ibox-body">
                    <?= $this->render('_tab_callback', ['dataProvider' => $callbackProvider]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Hoành thành</h2>
                </div>
                <div class="ibox-body">
                    <?= $this->render('_tab_done', ['dataProvider' => $completeProvider]) ?>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="remote-import" role="dialog">
        <div class="modal-dialog modal-xl  modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nhập liên hệ</h5>
                    <div>
                        <button type="button" class="btn filterData btn-primary">Lọc</button>
                        <button type="button" id="handleData" class="btn handleData btn-primary">Nhập liên hệ</button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">Đóng</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <a class="text-warning" href="<?= \yii\helpers\Url::toRoute(['/file/contacts_example.xlsx']) ?>"><i
                                class="fa fa-download"></i> File dữ liệu mẫu</a>
                    <div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="button" id="handleData" class="btn handleData btn-primary">Nhập liên hệ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?= $this->render("_modal_approve") ?>

    <div class="modal fade in" id="editRowModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa thông tin liên hệ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="resultRowImport"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="saveRowImport btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
<?php

$js = <<<JS
    initRemote("remote-import");
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