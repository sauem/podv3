<?php

use common\helper\Component;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContactsAssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contacts Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-12 text-right mb-2">
            <button data-remote="<?= \yii\helpers\Url::toRoute(['contacts-assignment/import']) ?>" data-toggle="modal"
                    data-target="#remote-import" class="btn btn-success">
                <i class="fa fa-file-excel-o"></i> Nhập liên hệ
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Chờ xử lý</h2>
                    <div class="ibox-tools">
                        <a data-toggle="collapse" href="#filter1"><i class="fa fa-filter"></i> Tìm kiếm</a>
                    </div>
                </div>
                <div class="ibox-body">
                    <?= $this->render('_tab_waiting', ['dataProvider' => $pendingProvider,'searchModel' => $searchModel]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Hẹn gọi lại</h2>
                </div>
                <div class="ibox-body">
                    <?= $this->render('_tab_callback', ['dataProvider' => $callbackProvider]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nhập liên hệ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <a class="text-warning" href="<?= \yii\helpers\Url::toRoute(['/file/contacts_example.xlsx']) ?>"><i
                                class="fa fa-download"></i> File dữ liệu mẫu</a>
                    <div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="button" id="handleData" class="btn btn-primary">Nhập liên hệ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$js = <<<JS
    initRemote("remote-import");
JS;
$this->registerJs($js);