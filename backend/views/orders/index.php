<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\helper\Component;
use backend\models\UserModel;
use backend\models\OrdersModel;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use backend\models\Payment;

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
            <?php $fullExportMenu = ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'asDropdown' => false,
                'columns' => [
                    'code',
                    'customer_name',
                    'customer_phone',
                    'customer_email',
                    'address',
                    'city',
                    'district',
                    'zipcode',
                    'country',
                    'total',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            if ($model->status == OrdersModel::_PENDING) {
                                return "OK";
                            }
                            return $model->status;
                        }
                    ],
                    [
                        'label' => 'Sale',
                        'attribute' => 'user_id',
                        'value' => function ($model) {
                            return $model->user->username;
                        }
                    ],
                    [
                        'label' => 'Maketer',
                        'attribute' => 'user_id',
                        'value' => function ($model) {
                            return $model->contacts[0]->contact->page->marketer;
                        }
                    ],
                    'order_note',
                    'vendor_note',
                    [
                        'label' => 'PTTT',
                        'attribute' => 'payment_method',
                        'value' => function ($model) {
                            return $model->payment ? $model->payment->name : "";
                        }
                    ],
                    [
                        'label' => 'Hóa đơn chuyển khoản',
                        'value' => function ($model) {
                            $bills = $model->billings;
                            $html = "";
                            if ($bills) {
                                foreach ($bills as $k => $bill) {
                                    $url = Url::toRoute("/file/$bill->path", 'http');
                                    $html .= "=HYPERLINK(\"$url\",\"Hóa đơn\")\n";
                                }
                            }
                            return $html;
                        }
                    ],
                    [
                        'label' => 'Giá vận chuyển',
                        'attribute' => 'shipping_price',
                        'value' => function ($model) {
                            return $model->shipping_price ? Helper::formatExcel($model->shipping_price) : "";
                        }
                    ],
                    [
                        'label' => 'Sku',
                        'value' => function ($model) {
                            $items = $model->items;
                            $html = "";
                            if ($items) {
                                foreach ($items as $item) {
                                    $html .= $item->product_sku . ",";
                                }
                            }
                            return substr($html, 0, -1);
                        }
                    ],
                    [
                        'label' => 'Số lượng',
                        'value' => function ($model) {
                            $items = $model->items;
                            $html = "";
                            if ($items) {
                                foreach ($items as $item) {
                                    $html .= $item->qty . ",";
                                }
                            }
                            return substr($html, 0, -1);
                        }
                    ],
                    [
                        'label' => 'Tổng hợp',
                        'value' => function ($model) {
                            $items = $model->items;
                            $html = "";
                            if ($items) {
                                foreach ($items as $item) {
                                    $html .= $item->qty . "*" . $item->product_sku . ",";
                                }
                            }
                            return substr($html, 0, -1);
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return date('d/m/Y', $model->created_at);
                        }
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => function ($model) {
                            return date('d/m/Y', $model->updated_at);
                        }
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_PDF => false,
                ],
            ]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                    'before' =>
                        Html::a("<i class='fa fa-cart-plus'></i> Tạo đơn", "javascript:;", ['class' => 'mr-2 btn createOrder btn-info', 'data-pjax' => '0']) .
                        (Helper::isAdmin() ?  Html::a('<i class="fa fa-trash"></i> Xóa lựa chọn', 'javascript:;',
                            [
                                'class' => 'btn deleteAll btn-warning',
                                'data-pjax' => '0',
                                'data-model' => $dataProvider->query->modelClass
                            ]) : ""),
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
                'export' => [
                    'itemsAfter' => [
                        '<div role="presentation" class="dropdown-divider"></div>',
                        '<div class="dropdown-header">Export All Data</div>',
                        $fullExportMenu
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
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}{block}{status}',
                        'headerOptions' => [
                            'width' => '10%',
                        ],
                        'buttons' => [
                            'update' => function ($url, $model) {
                                if ($model->hasLocked()) {
                                    return null;
                                }
                                return Html::a("<i class='fa fa-edit'></i> sửa đơn", 'javascript:;', [
                                    'class' => 'btn btn-sm bg-white mt-2',
                                    'data-toggle' => 'modal',
                                    'data-key' => $model->id,
                                    'data-target' => '#orderEdit',
                                    'data-backdrop' => "static",
                                    'data-keyboard' => "false"
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
                            },
                            'status' => function ($url, $model) {
                                return Html::a("<i class='fa fa-bar-chart'></i> Trạng thái", "javascript:;", [
                                    'class' => 'bg-white changeStatus btn btn-sm mt-2',
                                    'data-key' => $model->id,
                                    'data-pjax' => '0'
                                ]);
                            }
                        ]
                    ],
                ],
                'toolbar' => [
                    '{export} {toggleData}'
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            ]); ?>
        </div>
    </div>

<?= $this->render('_modal_edit') ?>
<?php
$js = <<<JS
    $("body").on("click",".changeStatus",function() {
        const _key = $(this).data('key');
         const { value: fruit } =  Swal.fire({
              title: 'Lự chọn trạng thái',
              input: 'select',
              inputOptions: {
                'pending': 'Đợi duyệt',
                'cancel': 'Hủy đơn',
              },
              inputPlaceholder: 'Lự chọn trạng thái',
              showCancelButton: true,
              inputValidator: (value) => {
                    $.ajax({
                        url : config.changeOrderStatus,
                        cache :false,
                        type : 'POST',
                        data : { status : value, key : _key},
                        success : function(res) {
                            if(res.success){
                                toastr.success("Thay đổi trạng thái thành công!");
                                __reloadData();
                            }else{
                                toastr.warning(res.msg);
                            }
                        }
                    })
              }
            })
    });
    $(".createOrder").click(function() {
            $("#orderEdit").modal({backdrop:"static"});
    });
JS;

$this->registerJs($js);

