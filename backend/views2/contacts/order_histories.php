<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use common\helper\Helper;
use kartik\grid\ExpandRowColumn;
use backend\models\ContactsModel;
use kartik\grid\ActionColumn;
use yii\helpers\Url;

?>
<div class="">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'responsiveWrap' => false,
        'layout' => "{summary}{items}\n{pager}",
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options' => [
                'id' => isset($id) ? "pjax-$id" : 'pjax-wait'
            ]
        ],
        'headerRowOptions' => [
            'class' => 'thead-light'
        ],
        'columns' => [
            [
                'label' => 'Contact code',
                'attribute' => 'code',
                'format' => 'html',
                'value' => function ($model) {
                    $html = Html::a($model->code, Url::toRoute(['orders/view', 'id' => $model->id])) . "<br>";
                    $html .= $model->contact->contact->phone;
                    return $html;
                }
            ],
            [
                'label' => 'Ngày tạo đơn',
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d/m/Y H:i:s', $model->created_at);
                }
            ],
            [
                'label' => 'Địa chỉ',
                'attribute' => 'address',
            ],
            [
                'label' => 'Zipcode',
                'attribute' => 'zipcode',
            ],
            [
                'label' => 'Loại sản phẩm',
                'value' => function ($model) {
                    if (!$model->contact->contact->page) {
                        return null;
                    }
                    return $model->contact->contact->page->category->name;
                }
            ],
            [
                'label' => 'Yêu cầu',
                'value' => function ($model) {
                    return $model->contact->contact->option;
                }
            ],
            [
                'label' => 'Ghi chú khách',
                'value' => function ($model) {
                    return $model->contact->contact->note;
                }
            ],
            [
                'label' => 'Sản phẩm',
                'format' => 'html',
                'value' => function ($model) {
                    $html = null;
                    if ($model->items) {
                        foreach ($model->items as $item) {
                            $html .= "<span class='badge badge-default'>{$item->qty} * {$item->product_sku}</span><br>";
                        }
                    }
                    return $html;
                }
            ],
            [
                'label' => 'Ghi chú đơn',
                'attribute' => 'status_note',
            ],
            [
                'label' => 'Doanh thu',
                'attribute' => 'total',
                'value' => function($model){
                    return "$model->total ". Helper::getCur($model->country);
                }
            ],
            [
                'label' => 'Ghi vận chuyển',
                'attribute' => 'order_note',
            ],
        ],
    ]) ?>
</div>

