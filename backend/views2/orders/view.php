<?php

use common\helper\Helper;
use yii\helpers\Html;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">
                        Khách hàng
                    </h4>
                    <div class="ibox-tools">
                        <button data-key="<?= $model->id ?>" class="btn export btn-sm btn-info"><i
                                    class="fa fa-cloud-download"></i> Xuất đơn hàng
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Tên:</td>
                            <td><?= $model->customer_name ?></td>
                        </tr>
                        <tr>
                            <td>Số điện thoại:</td>
                            <td><?= $model->customer_phone ?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?= $model->customer_email ?></td>
                        </tr>
                        <tr>
                            <td>Địa chỉ:</td>
                            <td><?= $model->address ?></td>
                        </tr>
                        <tr>
                            <td>Quận/Huyện:</td>
                            <td><?= $model->district ?></td>
                        </tr>
                        <tr>
                            <td>Tỉnh/Thành phố:</td>
                            <td><?= $model->city ?></td>
                        </tr>
                        <tr>
                            <td>Mã bưu điện:</td>
                            <td><?= $model->zipcode ?></td>
                        </tr>
                        <tr>
                            <td>Quốc gia:</td>
                            <td><?= Helper::getCountry($model->country) ?></td>
                        </tr>
                        <tr>
                            <td>Hình thức thanh toán</td>
                            <td><?= $model->payment ? $model->payment->name : "Không thiế lập" ?></td>
                        </tr>
                        <?php if ($model->billings) {
                            ?>
                            <tr>
                                <td>Hoá đơn chuyển khoản</td>
                                <td>
                                    <?php foreach ($model->billings as $billing) {
                                        echo "<a target='_blank' href='" . Helper::getImage($billing->path) . "'>";
                                        echo Html::img(Helper::getImage($billing->path), ['class' => 'img mb-2 img-fluid']);
                                        echo "</a>";
                                    } ?>
                                </td>
                            </tr>
                            <?php
                        } ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        Chi tiết đơn hàng
                    </h4>
                    <div class="d-flex">
                        <span class="badge text-danger mr-1"><?= date('d/m/Y H:i:s', $model->created_at) ?></span>
                        <?= \backend\models\OrdersModel::statusLabel($model->status) ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    $items = $model->items;
                    ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Lựa chọn</th>
                            <th>Tổng</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($items && !empty($items)) {
                            foreach ($items as $item) {
                                ?>
                                <tr>
                                    <td><?= $item->product->sku ?> | <?= $item->product->name ?></td>
                                    <td><?= $item->product_option ?></td>
                                    <td><?= Helper::money($item->price) ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<t><td colspan='3' class='text-warning text-center'>Không có sản phẩm nào</td></t>";
                        } ?>
                        <tr>
                            <td colspan="2">Phí vận chuyển</td>
                            <td><?= Helper::money($model->shipping_price) ?></td>
                        </tr>
                        </tbody>
                        <thead>
                        <tr>
                            <td colspan="2"><strong>Tổng hóa đơn</strong></td>
                            <td><strong><?= Helper::money($model->total) ?></strong></td>
                        </tr>
                        </thead>
                    </table>
                    <table class="table-borderless table">
                        <tr>
                            <td>
                                <p>Yêu cầu khách hàng
                                    : <?= Helper::checkEmpty($model->contact) ? 'Không tồn tại' : $model->cotact->contact->option ?></p>
                                <p>Ghi chú khách hàng
                                    : <?= Helper::checkEmpty($model->contact) ? 'không tồn tại' : $model->contact->contact->note ?></p>
                                <p>Ghi chú sale : <?= $model->order_note ?></p>
                                <p>Ghi chú vận chuyển : <?= $model->vendor_note ?></p>
                            </td>
                            <td>
                                <p>Ngày lên đơn: <?= date('d/m/Y H:i:s', $model->created_at) ?></p>
                                <p>Sale tạo đơn: <?= $model->user->username ?></p>
                                <p>
                                    Marketer: <?= Helper::checkEmpty($model->contact) ? 'Không tồn tại' : $model->contact->contact->page->user->username ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
$urlExport = \yii\helpers\Url::toRoute(['export/order']);
$js = <<<JS
    
JS;
$this->registerJs($js);