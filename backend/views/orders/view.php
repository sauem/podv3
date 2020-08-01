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
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">
                    Khách hàng
                </h2>
                <div class="ibox-tools">
                    <button data-key="<?= $model->id?>" class="btn export btn-sm btn-info"><i class="fa fa-cloud-download"></i> Xuất đơn hàng</button>
                </div>
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
                        <td><?=Helper::getCountry($model->country)?></td>
                    </tr>
                    <tr>
                        <td>Hình thức thanh toán</td>
                        <td><?= $model->payment ? $model->payment->name : "Không thiế lập"?></td>
                    </tr>
                    <?php if($model->billings){
                        ?>
                        <tr>
                            <td>Hoá đơn chuyển khoản</td>
                            <td>
                                <?php foreach ($model->billings as $billing){
                                    echo "<a target='_blank' href='".Helper::getImage($billing->path)."'>";
                                    echo Html::img(Helper::getImage($billing->path),['class' => 'img mb-2 img-fluid']);
                                    echo "</a>";
                                } ?>
                            </td>
                        </tr>
                    <?php
                    }?>
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
                            <th>Lựa chọn</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($items && !empty($items)){
                            foreach ($items as $item){
                                ?>
                                <tr>
                                    <td><?= $item->product->sku?> | <?= $item->product->name?></td>
                                    <td><?= $item->product_option?></td>
                                    <td><?= Helper::money($item->price)?></td>
                                </tr>
                        <?php
                            }
                        }else{
                            echo "<t><td colspan='3' class='text-warning text-center'>Không có sản phẩm nào</td></t>";
                        }?>
                    <tr>
                        <td colspan="2">Phí vận chuyển</td>
                        <td><?= Helper::money($model->shipping_price)?></td>
                    </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <td colspan="2"><strong>Tổng hóa đơn</strong></td>
                            <td><strong><?= Helper::money($model->total)?></strong></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">
                    Các liên hệ
                </h2>
            </div>
            <div class="ibox-body">
                <?php
                $contacts = $model->contacts;
                ?>
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>Đăng kí</th>
                        <th>Sản phẩm</th>
                        <th>Trang đích</th>
                        <th>Ngày đăng kí</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($contacts && !empty($contacts)){
                        foreach ($contacts as $contact){
                            ?>
                            <tr>
                                <td><?=
                                    "|".$contact->contact->name."<br>".
                                    "|<small class='text-warning'>{$contact->contact->code}</small><br>".
                                    "|".$contact->contact->address."<br>".
                                    "|".$contact->contact->zipcode

                                    ?></td>
                                <td><?=
                                    $contact->contact->page->product ? $contact->contact->page->product->name : "Không thiết lập". "<br>".
                                    $contact->contact->option
                                    ?></td>
                                <td>
                                    <?= Html::a($contact->contact->short_link,$contact->contact->link,['target' => '_blank'])?>
                                    <br>
                                    Marketer : <?= $contact->contact->page? $contact->contact->page->user->username : 'Không thiết lập'?>
                                </td>
                                <td><?= Helper::toDate($contact->updated_at)?></td>
                            </tr>
                            <?php
                        }
                    }else{
                        echo "<t><td colspan='4' class='text-warning text-center'>Không có sản phẩm nào</td></t>";
                    }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
$urlExport = \yii\helpers\Url::toRoute(['export/order']);
$js =<<<JS
    
JS;
$this->registerJs($js);