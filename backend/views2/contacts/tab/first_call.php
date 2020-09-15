<?php

use backend\models\UserModel;
use yii\helpers\Url;

?>
<div class="card card-body">
    <div class="d-flex justify-content-between">
        <div>
            <h5 style="cursor: pointer" data-toggle="tooltip" title="Click 2 lần để coppy"
                class="text-success card-title"><i
                        class="fe-phone-call"></i> <?= $info ? "<span ondblclick=\"coppy(this)\">0$info->phone</span> (coppy)" : "Chưa có liên hệ mới" ?>
            </h5>
            <div class="">
                <button data-pjax="0" class="btn btn-sm btn-outline-danger"><i class="fe-phone-off"></i> Sai số</button>
                <button data-pjax="0" class="btn btn-sm btn-outline-warning"><i class="fe-phone-forwarded"></i> Hẹn gọi
                    lại
                </button>
                <button data-pjax="0" class="btn btn-sm btn-outline-info"><i class="fe-phone-missed"></i> Thuê bao
                </button>
            </div>
        </div>
        <div class="d-flex">
            <h5>Sale: <a
                        href="<?= Url::toRoute(['user/view', 'id' => $user->getId()]) ?>"> <?= $user->identity->username ?></a>
            </h5>
            <h5 class="ml-3">Số điện thoại đã hoàn
                thành/ngày: <?= UserModel::completed() . "/" . $user->identity->phone_of_day ?></h5>
        </div>
    </div>
</div>

<div class="card-box">
    <ul class="nav nav-tabs tabs-line">
        <li class="nav-item">
            <a class="nav-link active" href="#first_wating" data-toggle="tab">
                <i class="ti-bar-chart"></i>
                Chờ xử lý (<?= $dataProvider->getcount()?>)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#first_callback" data-toggle="tab">
                <i class="ti-time"></i>
                Hẹn gọi lại/Thuê bao (<?= $callbackProvider->getcount()?>)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#first_failed" data-toggle="tab">
                <i class="fe-phone-missed"></i>
                Thất bại (<?= $failureProvider->getcount()?>)
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#first_ok" data-toggle="tab">
                <i class="fe-phone-outgoing"></i>
                Thành công (<?= $successProvider->getcount()?>)
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="first_wating">
            <?= $this->render("wait", ['dataProvider' => $dataProvider]) ?>
        </div>
        <div class="tab-pane fade" id="first_callback">
            <?= $this->render("wait", ['dataProvider' => $callbackProvider]) ?>
        </div>
        <div class="tab-pane fade" id="first_failed">
            <?= $this->render("done", ['dataProvider' => $failureProvider]) ?>
        </div>
        <div class="tab-pane fade" id="first_ok">
            <?= $this->render("done", ['dataProvider' => $successProvider]) ?>
        </div>
    </div>
</div>