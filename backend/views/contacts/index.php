<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContactsSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contacts Models';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Thông tin : <i class="fa fa-phone"></i> <?= $info->phone?></h2>
            </div>
            <div class="ibox-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Khách hàng</td>
                            <td><?= $info->name?></td>
                        </tr>
                        <tr>
                            <td>Địa chỉ</td>
                            <td><?= $info->address?></td>
                        </tr>
                        <tr>
                            <td>Zipcode</td>
                            <td><?= $info->zipcode?></td>
                        </tr>
                        <tr>
                            <td>IP</td>
                            <td><?= $info->ip?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="ibox">
            <div class="ibox-body">
                <ul class="nav nav-tabs tabs-line">
                    <li class="nav-item">
                        <a class="nav-link active" href="#wating" data-toggle="tab"><i class="ti-bar-chart"></i> Chờ xử lý</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#failure" data-toggle="tab"><i class="ti-settings"></i> Thất bại</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#success" data-toggle="tab"><i class="ti-announcement"></i> Thành công</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown">Hành động <i class="fa fa-angle-down"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <a id="changeStatus" class="dropdown-item" href="javascript:;">Thay đổi trạng thái</a>
                            <a id="createOrder" class="dropdown-item" href="javascript;;" data-toggle="tab">Tạo đơn hàng</a>
                        </ul>
                    </li>
                </ul>
                <div class="tab-content">

                    <div class="tab-pane fade show active" id="wating">
                        <?= $this->render('_tab_wait', ['dataProvider' => $dataProvider])?>
                    </div>
                    <div class="tab-pane fade" id="failure">
                        <?= $this->render('_tab_fail',['dataProvider' => $failureProvider])?>
                    </div>
                    <div class="tab-pane fade" id="success">
                        <?= $this->render('_tab_done',['dataProvider' => $successProvider])?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->render('_modal',['model' => $modelNote])?>
<?php
$route  = \yii\helpers\Url::toRoute(Yii::$app->controller->getRoute());
$js =<<<JS
    $("document").ready(function() {
        $("#changeStatus").click(function() {
            var keys = $('#w0').yiiGridView('getSelectedRows');
            alert(keys)
        });
    })
    
JS;

$this->registerJs($js);