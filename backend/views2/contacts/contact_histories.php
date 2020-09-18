<?php

use kartik\grid\GridView;
use \kartik\grid\SerialColumn;
use yii\helpers\Html;
use backend\models\ContactsModel;
use common\helper\Helper;

?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'responsive' => true,
    'layout' => "{items}\n{pager}",
    'pjax' => true,
    'pjaxSettings' => [
        'neverTimeout' => true,
        'options' => [
            'id' => 'pjax-contact_histories' . (isset($id) ? "_" . $id : "")
        ]
    ],
    'headerRowOptions' => [
        'class' => 'thead-light'
    ],
    'columns' => [
        [
            'label' => 'Số điện thoại',
            'attribute' => 'created_at',
            'format' => 'html',
            'value' => function ($model) {
                if (!$model->contact) {
                    return null;
                }
                return $model->contact->phone . "<span class='badge badge-info'>{$model->contact->code}</span>";
            }
        ],
        [
            'label' => 'Ngày liên hệ',
            'attribute' => 'created_at',
            'headerOptions' => ['width' => '15%'],
            'format' => 'raw',
            'value' => function ($model) {

                $html = "Lần xử lý cuối :<br>" . $model->created_at;
                if ($model->contact) {
                    $html .= "</br>Giờ gọi lại: <br>" . "<strong class='text-danger'>{$model->created_at}</strong>";
                }
                return $html;
            }
        ],
        [
            'label' => 'Sản phẩm',
            'attribute' => 'contact_id',
            'headerOptions' => ['width' => '30%'],
            'format' => 'raw',
            'value' => function ($model) {
                if (!$model->contact || !$model->contact->page) {
                    return null;
                }
                $page = $model->contact->page;
                $html = $page->product->name . "<br>";
                $html .= "<small>{$page->product->sku}</small>|";
                $html .= "<small>{$page->product->category->name}</small><br>";
                return $html;
            }
        ],
        [
            'label' => 'Trang đích',
            'format' => 'html',
            'headerOptions' => ['width' => '30%'],
            'value' => function ($model) {
                if (!$model->contact || !$model->contact->page) {
                    return null;
                }
                return Html::tag("p",
                    "<a target='_blank' href='{$model->contact->page->link}' >{$model->contact->page->link}  <i class='fa fa-chrome'></i></a><br>" .
                    "<small class='text-info'>address: <i>{$model->contact->address}</i></small><br>" .
                    "<small class='text-info'>zipcode: <i>{$model->contact->zipcode}</i></small><br>" .
                    ($model->contact->note ? "<small class='text-danger'>Note: <i>{$model->contact->note}</i></small><br>" : "")
                );
            }
        ],
        [
            'label' => 'Yêu cầu',
            'headerOptions' => [
                'width' => '15%'
            ],
            'value' => function ($model) {
                if (!$model->contact) {
                    return null;
                }
                return $model->contact->option;
            }
        ],
        ['label' => 'Trạng thái',
            'attribute' => 'status',
            'format' => 'html',
            'value' => function ($model) {
                return ContactsModel::label($model->status);
            }
        ],
        [
            'label' => 'Ghi chú sale',
            'attribute' => 'note'
        ]
    ],
]) ?>