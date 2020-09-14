<?php
?>

<div class="row">
    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title mb-3">Thông tin quản lý</h4>
            <table class="table table-borderless table-hover table-centered m-0">
                <tbody>
                <?php if (isset($assignment->user)) { ?>
                    <tr>
                        <td>Tên tài khoản:</td>
                        <td><?= $assignment->user->username ?></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><?= $assignment->user->email ?></td>
                    </tr>
                    <tr>
                        <td>Bộ phận:</td>
                        <td><?= $assignment->user->userRole->item_name ?></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td>Chưa phân bổ số điện thoại này</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title mb-3">Thông tin khách</h4>
            <table class="table table-borderless table-hover table-centered m-0">
                <tbody>
                <tr>
                    <td>Khách hàng:</td>
                    <td><?= $info->name ?></td>
                </tr>
                <tr>
                    <td>Số điện thoại:</td>
                    <td><?= $info->phone ?></td>
                </tr>
                <tr>
                    <td>Zipcode:</td>
                    <td><?= $info->zipcode ?></td>
                </tr>
                <tr>
                    <td>Địa chỉ:</td>
                    <td><?= $info->address ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <ul class="nav nav-tabs nav-bordered">
                <li class="nav-item">
                    <a href="#tab-b1" data-toggle="tab" aria-expanded="false" class="nav-link active">
                       <i class="fe-bar-chart"></i> Chờ xử lý (<?=$dataProvider->getCount()?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tab-b2" data-toggle="tab" aria-expanded="true" class="nav-link">
                        <i class="fe-clock"></i>  Hẹn gọi lại (<?=$callbackProvider->getCount()?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tab-b3" data-toggle="tab" aria-expanded="false" class="nav-link">
                        <i class="fe-user-x"></i> Hủy (<?=$failureProvider->getCount()?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tab-b4" data-toggle="tab" aria-expanded="false" class="nav-link">
                        <i class="fe-user-check"></i>  Thành công (<?=$successProvider->getCount()?>)
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-b1">
                    <?= $this->render('tab/tab', ['dataProvider' => $dataProvider]) ?>
                </div>
                <div class="tab-pane fade" id="tab-b2">
                    <?= $this->render('tab/tab', ['dataProvider' => $callbackProvider]) ?>
                </div>
                <div class="tab-pane fade" id="tab-b3">
                    <?= $this->render('tab/tab', ['dataProvider' => $failureProvider]) ?>
                </div>
                <div class="tab-pane fade" id="tab-b4">
                    <?= $this->render('tab/tab', ['dataProvider' => $successProvider]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewNote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Chi tiết liên hệ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>
