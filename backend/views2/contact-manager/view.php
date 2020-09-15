<?php

use kartik\grid\GridView;
use kartik\grid\ExpandRowColumn;
use yii\helpers\Html;
use backend\models\ContactsModel;
use common\helper\Helper;

?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Liên hệ <span class="badge badge-success" onclick="coppy(this)">0<?= $model->contact_phone?></span></h4>
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
                            'id' => 'pjax-wait'
                        ]
                    ],
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'columns' => [
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
                                    "<br><small>{$model->page->product->sku} |" . Helper::money($model->page->product->regular_price) . "</small> | <small><i>{$model->page->category->name}</i></small>" .
                                    "<br><small>{$model->option}</small>");
                            }
                        ],
                        [
                            'label' => 'Trang đích',
                            'attribute' => 'link',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if (!$model->page) {
                                    return null;
                                }
                                return Html::tag("p",
                                    "<a target='_blank' href='{$model->link}' >{$model->page->link}  <i class='fa fa-chrome'></i></a><br>" .
                                    "<small class='text-info'>CTCODE: <i><strong>{$model->code}</strong></i> | marketer: <strong>{$model->page->marketer}</strong> | Type : <strong>{$model->type}</strong></small><br>" .
                                    "<small class='text-info'>address: <i>{$model->address}</i></small><br>" .
                                    "<small class='text-info'>zipcode: <i>{$model->zipcode}</i></small><br>" .
                                    "<small class='text-danger'>Note: <i>{$model->note}</i></small><br>"

                                );
                            }
                        ],
                        [
                            'label' => 'Trạng thái',
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model) {
                                return \backend\models\ContactsModel::label($model->status);
                            }
                        ],
                        [
                            'label' => 'Ngày đặt hàng',
                            'attribute' => 'created_at',
                            'format' => 'html',
                            'value' => function ($model) {
                                return Html::tag("small", date("d/m/Y H:i:s", $model->register_time));
                            }
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Lịch sử liên hệ</h4>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $histories,
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
                                if ($model->contact->callback_time) {
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
                                if (!$model->contact->page || !$model->contact->page->product) {
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
                                if (!$model->contact->page) {
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
            </div>
        </div>
    </div>
</div>
