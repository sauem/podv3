<?php

use kartik\grid\GridView;
use \kartik\grid\SerialColumn;
use yii\helpers\Html;
use backend\models\ContactsModel;

?>
<div class="ibox">
    <div class="ibox-head">
        <h2 class="ibox-title">Lịch sử đơn hàng</h2>
    </div>
    <div class="ibox-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'layout' => "{items}\n{pager}",
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options' => [
                        'id' => 'pjax-histories'
                ]
            ],
            'headerRowOptions' => [
                'class' => 'thead-light'
            ],
            'columns' => [
                [
                    'class' => SerialColumn::class,
                ],
                [
                    'label' => 'Ngày tạo đơn',
                    'attribute' => 'created_at',
                    'format' => 'html',
                    'value' => function ($model) {
                        return date("H:i:s d/m/Y",$model->created_at);
                    }
                ],
                [
                    'label' => 'Khách hàng',
                    'attribute' => 'customer_phone',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->customer_phone;
                    }
                ],
                ['label' => 'sản phẩm', 'attribute' => 'customer_phone',
                    'format' => 'raw', 'value' => function ($model) {
                    $html = '';
                    foreach ($model->items as $item) {
                        $html .= "<span class='badge mb-1 badge-info'>{$item->product->sku} | {$item->product->name}</span><br>";
                    }
                    return $html;
                }],
                [
                    'label' => 'Tổng hóa đơn',
                    'attribute' => 'total',
                    'format' => 'html',
                    'value' => function ($model) {
                        return \common\helper\Helper::money($model->total);
                    }
                ],
                [
                    'label' => 'Trạng thái',
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function ($model) {
                        return ContactsModel::label($model->status);
                    }
                ],
                'order_note'
            ],
        ]) ?>
    </div>
</div>
