<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use common\helper\Helper;
use backend\models\OrdersModel;
use yii\helpers\Url;
use backend\models\UserModel;
?>

<div class="ibox">
    <div class="ibox-head">
        <h2 class="ibox-title">Danh sách đơn hàng</h2>
    </div>
    <div class="ibox-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'panel' => [
                'type' => GridView::TYPE_DEFAULT,
            ],
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 10],
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options' => [
                    'id' => 'pjax-order'
                ]
            ],

            'headerRowOptions' => [
                'class' => [
                    'thead-light'
                ]
            ],
            'columns' => [
                ['class' => \kartik\grid\CheckboxColumn::class],
                [
                    'attribute' => 'customer_name',
                    'format' => 'html',
                    'footer' => '<strong>Tổng </strong>',
                    'value' => function ($model) {
                        $html = "<a href='" . Url::toRoute(['view', 'id' => $model->id]) . "' class='badge-info badge'>{$model->code}</a><br>";
                        $html .= "<a data-pjax='0' href='" . Url::toRoute(['view', 'id' => $model->id]) . "'>{$model->customer_name}</a><br>";
                        $html .= $model->customer_phone . "<br>";
                        $html .= $model->customer_email . '<br>';
                        return $html;
                    }
                ],
                [
                    'label' => 'Địa chỉ',
                    'attribute' => 'address',
                    'format' => 'html',
                    'value' => function ($model) {
                        $html = $model->address . "<br>";
                        $html .= $model->district . "<br>";
                        $html .= $model->city . "<br>";
                        $html .= Helper::getCountry($model->country);
                        return $html;
                    }
                ],
                [
                    'label' => 'Trạng thái',
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return OrdersModel::statusLabel($model->status);
                    }
                ],
                [
                    'label' => 'Người tạo đơn',
                    'attribute' => 'total',
                    'format' => 'html',
                    'value' => function ($model) {
                        if(!$model->user){
                            return null;
                        }
                        return $model->user->username;
                    }
                ],
                ['label' => 'sản phẩm', 'attribute' => 'customer_phone',
                    'format' => 'raw', 'value' => function ($model) {
                    $html = '';
                    foreach ($model->items as $item) {
                        $html .= "<span class='badge mb-1 badge-default'>{$item->product->sku} | {$item->product->name} | x{$item->qty}</span><br>";
                    }
                    return $html;
                }],
                [
                    'label' => 'Tổng đơn',
                    'attribute' => 'total',
                    'format' => 'html',
                    'footer' => Helper::money($dataProvider->query->sum('total')),
                    'value' => function ($model) {
                        return Helper::money($model->total);
                    }
                ],
                [
                    'label' => 'Ngày tạo đơn',
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $create = Html::tag("span",
                            "<i class='fa fa-plus'></i> | " . date('H:i:s d/m/Y', $model->created_at),
                            [
                                'class' => 'badge badge-default ',
                                'data-toggle' => 'tooltip',
                                'title' => 'giờ tạo đơn'
                            ]);
                        $canEdit = Html::tag("span", "<i class='fa fa-lock'></i> | " . date('H:i:s d/m/Y', $model->block_time),
                            [
                                'class' => 'badge badge-default mt-2',
                                'data-toggle' => 'tooltip',
                                'title' => 'Giờ khóa đơn'
                            ]);
                        if ($model->block_time == 0) {
                            $canEdit = Html::tag("span", "<i class='fa fa-lock'></i> | đã khóa chỉnh sửa", [
                                'class' => 'badge badge-default mt-2',
                                'data-toggle' => 'tooltip',
                                'title' => 'Trạng thái khóa'
                            ]);
                        }
                        return "$create<br>$canEdit";
                    }
                ],
            ],
        ]); ?>
    </div>
</div>
