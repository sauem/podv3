<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\helper\Component;
use backend\models\UserModel;

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

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{pager}",
                'resizableColumns' => false,
                'showFooter' => true,
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
                        'format' => 'raw',
                        'value' => function ($model) {
                            $create = Html::tag("span",
                                "<i class='fa fa-plus'></i> | " . date('H:i:s d/m/Y', $model->created_at),
                                [
                                    'class' => 'badge badge-info ',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'giờ tạo đơn'
                                ]);
                            $canEdit = Html::tag("span", "<i class='fa fa-lock'></i> | " . date('H:i:s d/m/Y', $model->block_time),
                                [
                                    'class' => 'badge badge-warning mt-2',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Giờ khóa đơn'
                                ]);
                            if ($model->block_time == 0) {
                                $canEdit = Html::tag("span", "<i class='fa fa-lock'></i> | đã khóa chỉnh sửa", [
                                    'class' => 'badge badge-danger mt-2',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Trạng thái khóa'
                                ]);
                            }
                            return "$create<br>$canEdit";
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{update}{export}{block}',
                        'headerOptions' => [
                            'width' => '10%',
                        ],
                        'buttons' => [
                            'view' => function ($url) {
                                return Component::view($url);
                            },
                            'update' => function ($url, $model) {
                                if ($model->hasLocked()) {
                                    return null;
                                }
                                return Html::a("<i class='fa fa-edit'></i> sửa đơn", 'javascript:;', [
                                    'class' => 'btn btn-sm bg-white mt-2',
                                    'data-toggle' => 'modal',
                                    'data-key' => $model->id,
                                    'data-target' => '#orderEdit'
                                ]);
                            },
                            'export' => function ($url, $model) {
                                return Html::a("<i class='fa fa-cloud-download'></i> Xuất đơn", "javascript:;", [
                                    'class' => 'bg-white export btn btn-sm mt-2',
                                    'data-key' => $model->id,
                                    'data-pjax' => '0'
                                ]);
                            },
                            'block' => function ($url, $model) {
                                if (!Helper::userRole(UserModel::_ADMIN)) {
                                    return null;
                                }
                                $button = Html::a("<i class='fa fa-lock'></i> Khóa sửa", 'javascript:;', [
                                    'class' => 'bg-white block btn btn-sm mt-2',
                                    'data-key' => $model->id,
                                    'data-pjax' => '0'
                                ]);
                                if ($model->hasLocked()) {
                                    $button = Html::a("<i class='fa fa-lock'></i> Mở khóa", 'javascript:;', [
                                        'class' => 'bg-white block btn btn-sm mt-2',
                                        'data-key' => $model->id,
                                        'data-type' => 'open',
                                        'data-pjax' => '0'
                                    ]);
                                }
                                return $button;
                            }
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>

<?= $this->render('_modal_edit') ?>


