<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContactsAssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contacts Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ibox">
    <div class="ibox-head">
        <h2 class="ibox-title">Danh sách liên hệ</h2>
    </div>
    <div class="ibox-body">
        <ul class="nav nav-tabs tabs-line">
            <li class="nav-item">
                <a class="nav-link active" href="#all" data-toggle="tab"><i class="ti-cloud"></i> Tất cả liên hệ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="#wating" data-toggle="tab"><i class="ti-bar-chart"></i> Chưa phân bổ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#failure" data-toggle="tab"><i class="ti-settings"></i> Đã phân bổ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#success" data-toggle="tab"><i class="ti-announcement"></i> Hoàn thành</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all">
                <?= $this->render('_tab_all', ['dataProvider' => $dataProvider])?>
            </div>
            <div class="tab-pane fade" id="wating">
                <?= $this->render('_tab_waiting')?>
            </div>
            <div class="tab-pane fade" id="failure">
                <?= $this->render('_tab_approved')?>
            </div>
            <div class="tab-pane fade" id="success">
                <?= $this->render('_tab_waiting')?>
            </div>
        </div>
    </div>
</div>