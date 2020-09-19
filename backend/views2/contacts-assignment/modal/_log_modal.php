<?php
use yii\helpers\Url;
?>
<div class="modal fade" tabindex="-1" id="logs-import" role="dialog">
    <div class="modal-dialog modal-xl  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nhập lịch sử liên hệ</h5>
                <div class="btn-group">
                    <button data-action="logs" class="btn handleData btn-sm btn-success">
                        <i class="fe-download-cloud"></i> Nhập liên hệ
                    </button>
                    <button type="button"
                            class="btn btn-sm btn-secondary"
                            data-dismiss="modal">
                        <i class="fe-x"></i> Đóng
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-border text-success m-2" role="status"></div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a class="text-info" href="<?= Url::toRoute(['/file/log_example.xlsx']) ?>"><i
                        class="fe-download"></i> File dữ liệu mẫu</a>
                <div>
                    <div class="btn-group">
                        <button data-action="logs" class="btn handleData btn-sm btn-success">
                            <i class="fe-download-cloud"></i> Nhập liên hệ
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-secondary"
                                data-dismiss="modal">
                            <i class="fe-x"></i> Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
