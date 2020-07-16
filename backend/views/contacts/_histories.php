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
            ],
            'headerRowOptions' => [
                'class' => 'thead-light'
            ],
            'columns' => [
                [
                    'class' => SerialColumn::class,
                ],
                'created_at:datetime',
                ['label' => 'sản phẩm', 'attribute' => 'customer_phone',
                    'format' => 'raw', 'value' => function ($model) {
                    $html = '';
                    foreach ($model->items as $item) {
                        $html .= "<span class='badge badge-info'>{$item->product->sku} | {$item->product->name}</span><br>";
                    }
                    return $html;
                }],
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
