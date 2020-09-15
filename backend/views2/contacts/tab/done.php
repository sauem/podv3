<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use common\helper\Helper;
use kartik\grid\ExpandRowColumn;
use backend\models\ContactsModel;
use kartik\grid\ActionColumn;

?>
<div class="">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
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
                    $html = $model->code . "<br>";
                    $html .= ContactsModel::label($model->status);
                    return $html;
                }
            ],
            [
                'label' => 'Ngày đặt hàng',
                'attribute' => 'register_time',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::tag("p", date("d/m/Y H:i:s", $model->register_time));
                }
            ],
            [
                'label' => 'Ngày gọi cuối',
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('d/m/Y H:i:s', $model->created_at);
                }
            ],
            [
                'label' => 'Trang đích',
                'attribute' => 'link',
                'headerOptions' => [
                    'width' => '15%'
                ],
                'format' => 'raw',
                'value' => function ($model) {
                    if (!$model->page) {
                        return null;
                    }
                    return Html::tag("p",
                        "<a target='_blank' href='" . Helper::link($model->link) . "' >{$model->page->link}  <i class='fa fa-chrome'></i></a><br>" .
                        "<small class='text-warning'>Địa chỉ: <i>{$model->address}</i></small><br>" .
                        "<small class='text-warning'>Zipcode: <i>{$model->zipcode}</i></small><br>"
                    );
                }
            ],
            [
                'label' => 'sản phẩm',
                'attribute' => 'category_id',
                'format' => 'html',
                'value' => function ($model) {
                    if (!$model->page || !$model->page->product) {
                        return null;
                    }
                    return Html::tag("p",
                        $model->page->product->name .
                        "<br><small>{$model->page->product->sku} </small> | <small><i>{$model->page->category->name}</i></small>");
                }
            ],
            [
                'label' => 'Yêu cầu của khách',
                'attribute' => 'option',
                'headerOptions' => [
                    'width' => '15%'
                ]
            ],
            [
                'label' => 'Ghi chú của khách',
                'attribute' => 'note',
            ],
        ],
    ]) ?>
</div>