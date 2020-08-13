<?php

use kartik\grid\GridView;
use kartik\grid\ExpandRowColumn;
use yii\helpers\Html;
use backend\models\ContactsModel;
use common\helper\Helper;

?>
<div class="row">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Tất cả liên hệ</h2>
            </div>
            <div class="ibox-body">
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
                        [
                            'class' => ExpandRowColumn::class,
                            'width' => '50px',
                            'value' => function ($model, $key, $index, $column) {
                                return GridView::ROW_EXPANDED;
                            },
                            'detail' => function ($model, $key, $index, $column) {
                                return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
                            },
                            'headerOptions' => ['class' => 'expand-area'],
                            'expandOneOnly' => true,
                            'detailRowCssClass' => GridView::TYPE_DEFAULT
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Lịch sử liên hệ</h2>
            </div>
            <div class="ibox-body">
                <?= GridView::widget([
                    'dataProvider' => $histories,
                    'responsive' => true,
                    'layout' => "{items}\n{pager}",
                    'pjax' => true,
                    'pjaxSettings' => [
                        'neverTimeout' => true,
                        'options' => [
                            'id' => 'pjax-contact_histories'
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
                                $html = "<strong>{$model->contact->phone}</strong><span class='badge badge-info'>{$model->contact->code}</span><br>";
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
                                $html .= "<small>" . Helper::money($page->product->regular_price) . "</small>|";
                                $html .= "<small>{$page->product->category->name}</small><br>";
                                $html .= "<small>{$model->contact->option}</small>";

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
                                    "<small class='text-info'>CTCODE: <i><strong>{$model->contact->code}</strong></i> | Marketer: <strong>{$model->contact->page->marketer}</strong> | Type : {$model->contact->type}</small><br>" .
                                    "<small class='text-info'>address: <i>{$model->contact->address}</i></small><br>" .
                                    "<small class='text-info'>zipcode: <i>{$model->contact->zipcode}</i></small><br>" .
                                    "<small class='text-danger'>Note: <i>{$model->contact->note}</i></small><br>"

                                );
                            }
                        ],
                        [
                            'label' => 'Ngày liên hệ',
                            'attribute' => 'created_at',
                            'headerOptions' => ['width' => '15%'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                $html = "Liên hệ cuối :<br>" . $model->created_at;
                                if ($model->contact->callback_time) {
                                    $html .= "</br>Giờ gọi lại: <br>" . "<strong class='text-danger'>{$model->created_at}</strong>";
                                }
                                return $html;
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
                        [
                            'label' => 'Ghi chú',
                            'attribute' => 'note'
                        ]
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
