<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\helper\Component;
use backend\models\UserModel;
use backend\models\OrdersModel;
use yii\helpers\Url;
use kartik\export\ExportMenu;
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
            <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'customer_name',
                    'customer_phone',
                    'customer_email',
                    'address',
                    'city',
                    'district',
                    'zipcode',
                    'country',
                    'total',
                    'order_note',
                    'status_note',
                    'status',
                    'vendor_note',
                    'shipping_price',
                    'payment_method',
                    'created_at',
                    'updated_at'
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_PDF => false,
                ],
            ]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{pager}",
                //'resizableColumns' => false,
                'export' => [
                    'showConfirmAlert' => false,
                    'target' => GridView::TARGET_BLANK
                ],
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
                    ['class' => \kartik\grid\CheckboxColumn::class],
                    [
                        'attribute' => 'customer_name',
                        'format' => 'html',
                        'footer' => '<strong>Tổng </strong>',
                        'value' => function ($model) {
                            $html = "<a href='" . Url::toRoute(['view', 'id' => $model->id]) . "' class='badge-info badge'>{$model->contacts[0]->contact->code}</a><br>";
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
                'approved': 'Duyệt đơn',
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
JS;

$this->registerJs($js);

