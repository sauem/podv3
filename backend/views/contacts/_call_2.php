<?php
use backend\models\ContactsModel;
use backend\models\ContactsAssignment;
use yii\helpers\ArrayHelper;
use common\helper\Helper;
use backend\models\UserModel;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>
<div class="row">
    <div class="col-md-12">
        <?= $this->render('_collapse_order', ['model' => $order]) ?>
    </div>

    <div class="col-md-4">
        <?php Pjax::begin([
            'id' => 'pjax-info2'
        ]) ?>
        <div class="ibox">
            <div class="ibox-head">
                <h2 style="cursor: pointer" data-toggle="tooltip" title="Click 2 lần để coppy"
                    class="text-warning ibox-title"><i
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
                        <a class="nav-link active" href="#_wating" data-toggle="tab"><i
                                    class="ti-bar-chart"></i> Chờ
                            xử lý (<?= $dataProvider->getCount() ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#_callback" data-toggle="tab">
                            <i class="ti-time"></i> Gọi lại (<?= $callbackProvider->getCount() ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#_failure" data-toggle="tab"><i class="ti-settings"></i>
                            Thất
                            bại (<?= $failureProvider->getCount() ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#_success" data-toggle="tab"><i
                                    class="ti-announcement"></i> Thành
                            công (<?= $successProvider->getCount() ?>)</a>
                    </li>

                </ul>
                <div class="tab-content">


                    <?= $this->render('_search', ['model' => $searchModel]) ?>
                    <div class="tab-pane fade show active" id="_wating">
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
                        <?= $this->render('_tab_wait', ['dataProvider' => $dataProvider,'id' => '_waiting2']) ?>
                    </div>
                    <div class="tab-pane fade" id="_callback">
                        <div class="mb-2">
                            <?php if (Helper::userRole(UserModel::_ADMIN)) {
                                ?>
                                <a id="changeStatus" class="btn btn-sm btn-info" href="#">Thay đổi trạng
                                    thái</a>
                            <?php } ?>
                            <button id="createOrder" class="btn btn-sm btn-info">Tạo đơn hàng</button>
                        </div>

                        <?= $this->render('_tab_callback', ['dataProvider' => $callbackProvider,'id' => '_callback2']) ?>
                    </div>
                    <div class="tab-pane fade" id="_failure">
                        <?= $this->render('_tab_fail', ['dataProvider' => $failureProvider,'id' => '_failure2']) ?>
                    </div>
                    <div class="tab-pane fade" id="_success">
                        <?= $this->render('_tab_done', ['dataProvider' => $successProvider, 'id' => '_success2']) ?>
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