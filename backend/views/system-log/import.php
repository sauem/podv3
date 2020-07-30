<?php

use kartik\grid\GridView;
use yii\helpers\Html;
?>

<div class="ibox">
    <div class="ibox-head">
        <h2 class="ibox-title">Lịch sử nhập liệu</h2>
    </div>
    <div class="ibox-body">
        <?=
        \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                 ['class' => '\kartik\grid\SerialColumn'],
                'name',
                'line',
                'message',
                'created_at:date'
            ]
        ])
        ?>

    </div>
</div>