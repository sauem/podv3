<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\grid\SerialColumn;
use kartik\grid\ActionColumn;
use backend\models\ContactsModel;

/* @var $this yii\web\View */
/* @var $model backend\models\ContactsAssignment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contacts Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="row">
    <div class="col-md-6">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Tài khoản phân bổ</h2>
            </div>
            <div class="ibox-body">
                <table class="table">
                    <?php if(isset($assignment->user)) { ?>
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
                    <?php }else{ ?>
                        <tr>
                            <td>Chưa phân bổ số điện thoại này</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Khách hàng : <strong><?= $info->name ?></strong></h2>
            </div>
            <div class="ibox-body">
                <table class="table">
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
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-body">
                <ul class="nav nav-tabs tabs-line">
                    <li class="nav-item">
                        <a class="nav-link active" href="#wating" data-toggle="tab"><i class="ti-bar-chart"></i> Chờ
                            xử lý</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#callback" data-toggle="tab">
                            <i class="ti-time"></i> Hẹn gọi lại</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#success" data-toggle="tab"><i class="ti-announcement"></i> Thành
                            công</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="wating">
                        <?= $this->render('_table', ['dataProvider' => $dataProvider]) ?>
                    </div>
                    <div class="tab-pane fade" id="callback">
                        <?= $this->render('_table', ['dataProvider' => $callbackProvider]) ?>
                    </div>
                    <div class="tab-pane fade" id="success">
                        <?= $this->render('_table', ['dataProvider' => $successProvider]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Lịch sử đơn hàng</h2>
            </div>
            <div class="ibox-body">
                <?= GridView::widget([
                    'dataProvider' => $histories,
                    'responsive' => true,
                    'layout' => "{items}\n{pager}",
                    'pjax' => true,
                    'pjaxSettings' => [
                        'neverTimeout' => true,
                    ],
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'columns' => [
                        [
                            'class' => SerialColumn::class,
                        ],
                        'created_at',
                        [
                            'label' => 'Sản phẩm',
                            'attribute' => 'contacts.name',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if(!$model->contact->page->product){
                                    return "không thiết lập";
                                }
                                $html = $model->contact->page->product->name . "<br>";
                                $html .= $model->contact->page->product->sku . " | ";
                                $html .= $model->contact->page->product->regular_price . " | ";
                                $html .= $model->contact->page->product->category->name . "<br>";
                                $html .= $model->contact->option;
                                return $html;
                            }
                        ],
                        'total',
                        [
                            'label' => 'Trạng thái',
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model) {
                                return ContactsModel::label($model->status);
                            }
                        ],
                        'note'
                    ],
                ]) ?>
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
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</div>
