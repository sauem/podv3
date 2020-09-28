<?php

use backend\models\ContactsLog;
use kartik\grid\GridView;

?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Bảng điểm SALE</h4>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'layout' => "{summary}{items}\n{pager}",
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options' => [
                    'id' => 'pjax-brank'
                ]
            ],
            'headerRowOptions' => [
                'class' => 'thead-light'
            ],
            'columns' => [
                [
                    'label' => 'Nhân viên',
                    'attribute' => 'sale',
                ],
                [
                    'label' => 'Lead OK',
                    'attribute' => 'ok'
                ],
                [
                    'label' => 'Thuê bao',
                    'value' => 'pending'
                ],
                [
                    'label' => 'Lead hủy',
                    'value' => 'cancel'
                ],
                [
                    'label' => 'Gọi lại lần 1',
                    'value' => 'callback_1'
                ],
                [
                    'label' => 'Gọi lại lần 2',
                    'value' => 'callback_2'
                ],
                [
                    'label' => 'Sai số/ trùng/ bỏ qua',
                    'attribute' => 'failed',
                ],
                [
                    'label' => 'Điểm',
                    'value' => function ($model) {
                        $point = 0;
                        $point += $model['ok'] * 1;
                        $point += $model['cancel'] * 1;
                        $point += $model['pending'] * 1;
                        $point += $model['callback_1'] * 0.5;
                        $point += $model['callback_2'] * 1;
                        return $point;
                    }
                ],
            ],
        ]) ?>
    </div>
</div>
