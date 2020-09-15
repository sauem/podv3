<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use common\helper\Helper;
use kartik\grid\ExpandRowColumn;
use backend\models\ContactsModel;
use kartik\grid\ActionColumn;

?>
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
            'class' => CheckboxColumn::class,
            'checkboxOptions' => function ($model) {
                if (!$model->page) {
                    return ['data-cate' => null];
                }
                return ['data-cate' => $model->page->category_id];
            }
        ],
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
            'label' => 'Trang đích',
            'attribute' => 'link',
            'headerOptions' => [
                'width' => '15%'
            ],
            'format' => 'raw',
            'value' => function ($model) {
                $html = null;
                if($model->page){
                    $html.= "<a target='_blank' href='" . Helper::link($model->link) . "' >{$model->page->link}  <i class='fa fa-chrome'></i></a><br>";
                    $html.= "<small class='text-warning'>Địa chỉ: <i>{$model->address}</i></small><br>";
                    $html.= "<small class='text-warning'>Zipcode: <i>{$model->zipcode}</i></small>";
                }
                return $html;
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
        [
            'class' => ActionColumn::className(),
            'header' => 'Thao tác',
            'width' => '140px',
            'template' => '{cancel} {createOrder}',
            'buttons' => [
                'cancel' => function ($url, $model) {
                    return Html::button("<i class='fe-phone-missed'></i> Hủy", [
                        'class' => 'btn btn-sm cancelButton btn-outline-warning w-100',
                        'data-toggle' => 'tooltip',
                        'data-key' => $model->id,
                        'title' => 'Hủy contact/Trùng số',
                        'data-pjax' => '0'
                    ]);
                },
                'createOrder' => function ($url, $model) {
                    return Html::button("<i class='fe-shopping-cart'></i> Tạo đơn", [
                        'class' => 'createOrder btn btn-sm mt-2 btn-outline-success w-100',
                        'data-key' => $model->id,
                        'data-toggle' => 'tooltip',
                        'title' => 'Tạo đơn hàng từ liên hệ này',
                        'data-pjax' => '0'
                    ]);
                }
            ]
        ],
    ],
]) ?>
