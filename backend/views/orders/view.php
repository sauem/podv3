<?php
use common\helper\Helper;
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">
                    Khách hàng
                </h2>
            </div>
            <div class="ibox-body">
                <table class="table">
                    <tr>
                        <td>Tên:</td>
                        <td><?= $model->customer_name?></td>
                    </tr>
                    <tr>
                        <td>Số điện thoại:</td>
                        <td><?= $model->customer_phone?></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><?= $model->customer_email?></td>
                    </tr>
                    <tr>
                        <td>Địa chỉ:</td>
                        <td><?= $model->address?></td>
                    </tr>
                    <tr>
                        <td>Quận/Huyện:</td>
                        <td><?= $model->district?></td>
                    </tr>
                    <tr>
                        <td>Tỉnh/Thành phố:</td>
                        <td><?= $model->city?></td>
                    </tr>
                    <tr>
                        <td>Mã bưu điện:</td>
                        <td><?= $model->zipcode?></td>
                    </tr>
                    <tr>
                        <td>Quốc gia:</td>
                        <td><?= \common\helper\Helper::getCountry($model->country)?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">

        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">
                    Chi tiết đơn hàng
                </h2>
            </div>
            <div class="ibox-body">
                <p>Ghi chú: <?= $model->order_note?></p>
                <?php
                    $items = $model->items;
                ?>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($items && !empty($items)){
                            foreach ($items as $item){
                                ?>
                                <tr>
                                    <td><?= $item->product->sku?> | <?= $item->product->name?></td>
                                    <td><?= $item->qty?></td>
                                    <td><?= Helper::money($item->price)?></td>
                                    <td><?= Helper::money($item->price * $item->qty)?></td>
                                </tr>
                        <?php
                            }
                        }else{
                            echo "<t><td colspan='4' class='text-warning text-center'>Không có sản phẩm nào</td></t>";
                        }?>
                    </tbody>
                    <thead>
                        <tr>
                            <td colspan="3"><strong>Tổng hóa đơn</strong></td>
                            <td><strong><?= Helper::money($model->total)?></strong></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
