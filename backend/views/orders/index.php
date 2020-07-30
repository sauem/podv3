<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\helper\Component;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrdersSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders Models';
$this->params['breadcrumbs'][] = $this->title;

use common\helper\Helper;

?>
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">
            Danh sách order
        </div>
    </div>
    <div class="ibox-body">
        <?= $this->render("_search", ['model' => $searchModel]) ?>
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{pager}",
            'resizableColumns' => false,
            'showFooter' => true,
            'headerRowOptions' => [
                'class' => [
                    'thead-light'
                ]
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'customer_name',
                    'format' => 'html',
                    'footer' => '<strong>Tổng </strong>',
                    'value' => function ($model) {
                        $html = $model->customer_name . "<br>";
                        $html .= $model->customer_phone . "<br>";
                        $html .= $model->customer_email;
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
                "zipcode",
                [
                    'label' => 'Số liên hệ',
                    'attribute' => 'total',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->getContacts()->count();
                    }
                ],
                [
                    'label' => 'Người tạo đơn',
                    'attribute' => 'total',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->user->username;
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
                    'format' => 'html',
                    'value' => function ($model) {
                        return date('H:i:s d/m/Y');
                    }
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}{export}',
                    'buttons' => [
                        'view' => function ($url) {
                            return Component::view($url);
                        },
                        'export' => function($url,$model){
                            return Html::a("<i class='fa fa-cloud-download'></i> Xuất đơn","javascript:;",[
                               'class' => 'bg-white export btn btn-sm mt-2',
                                'data-key' => $model->id,
                                'data-pjax' => '0'
                            ]);
                        }
                    ]
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>

    </div>

</div>
