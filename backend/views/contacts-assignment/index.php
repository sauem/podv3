<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContactsAssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contacts Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-md-6">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Chờ xử lý</h2>
            </div>
            <div class="ibox-body">
                <?= $this->render('_tab_waiting', ['dataProvider' => $pendingProvider])?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Hẹn gọi lại</h2>
            </div>
            <div class="ibox-body">
                <?= $this->render('_tab_callback', ['dataProvider' => $callbackProvider])?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Hoành thành</h2>
            </div>
            <div class="ibox-body">
                <?= $this->render('_tab_done', ['dataProvider' => $completeProvider])?>
            </div>
        </div>
    </div>
</div>