<?php

use backend\models\Warehouse;
use common\helper\Component;
use common\helper\Helper;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url; ?>
    <div class="card-box">
        <ul class="nav nav-tabs nav-bordered">
            <li class="nav-item">
                <a href="#home-b1" data-toggle="tab" aria-expanded="false" class="nav-link active">
                    Kho sản phẩm
                </a>
            </li>
            <li class="nav-item">
                <a href="#profile-b1" data-toggle="tab" aria-expanded="true" class="nav-link">
                    Kho chi nhánh
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="home-b1">
                <?= GridView::widget([
                    'dataProvider' => $productWarehouse,
                    'columns' => [
                        [
                            'attribute' => 'sku',
                            'label' => 'Sản phẩm',
                        ],
                        [
                            'attribute' => 'name',
                            'label' => 'Danh mục',
                        ],
                        [
                            'attribute' => 'import',
                            'label' => 'Nhập',
                            'value' => function ($model) {
                                return $model['import'] ? $model['import'] : '--';
                            }
                        ],
                        [
                            'attribute' => 'export',
                            'label' => 'Đã xuất',
                            'value' => function ($model) {
                                return $model['export'] ? $model['export'] : '--';
                            }
                        ],
                        [
                            'attribute' => 'refund',
                            'label' => 'Hoàn',
                            'value' => function ($model) {
                                return $model['refund'] ? $model['refund'] : '--';
                            }
                        ],
                        [
                            'attribute' => 'broken',
                            'label' => 'Hỏng',
                            'value' => function ($model) {
                                return $model['broken'] ? $model['broken'] : '--';
                            }
                        ],
                        [
                            'label' => 'Tồn',
                            'value' => function ($model) {
                                return $model['import'] + $model['refund'] - $model['export'] + $model['broken'];
                            }
                        ],
                        [
                            'label' => 'Chưa xuất hàng',
                            'value' => function ($model) {
                                return isset($model['orderItems']) ? $model['orderItems']['pending'] : '--';
                            }
                        ],
                    ]
                ]); ?>
            </div>
            <div class="tab-pane" id="profile-b1">
                <div class="text-right mb-3">
                    <button
                            data-remote="<?= Url::toRoute(['/bill-lading/form']) ?>"
                            data-toggle="modal"
                            data-target="#modal-warehouse"
                            class="btn-xs btn btn-info">
                        <i class="fe-plus"></i> Thêm kho
                    </button>
                    <button class="btn-xs btn btn-success"><i class="fe-download"></i> Nhập kho</button>
                </div>

                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'name',
                        [
                            'attribute' => 'Thị trường',
                            'format' => 'html',
                            'value' => function ($model) {
                                return Helper::getCountry($model->country);
                            }
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model) {
                                return Warehouse::labelStatus($model->status);
                            }
                        ],
                        'note',
                        [
                            'class' => ActionColumn::class,
                            'template' => '{product}{update}{delete}',
                            'header' => 'Thao tác',
                            'width' => '25%',
                            'buttons' => [
                                'product' => function ($url, $model) {
                                    $url = Url::toRoute(['view', 'id' => $model->id]);
                                    return Html::a('<i class="fe-box"></i> sản phẩm', $url, [
                                        'class' => 'btn btn-info btn-xs m-1',
                                        'data-pjax' => '0'
                                    ]);
                                },
                                'update' => function ($url, $model) {
                                    $url = Url::toRoute(['/bill-lading/form', 'id' => $model->id]);
                                    return Component::update($url, true, '#modal-warehouse');
                                },
                                'delete' => function ($url, $model) {
                                    $url = Url::toRoute(['warehouse-delete', 'id' => $model->id]);
                                    return Component::delete($url);
                                }
                            ]
                        ]
                    ]
                ]); ?>
            </div>
        </div>
    </div>

    <div id="modal-warehouse" role="dialog" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm kho hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-grow text-success" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$js = <<<JS
    initRemote('modal-warehouse');
JS;
$this->registerJs($js);
?>