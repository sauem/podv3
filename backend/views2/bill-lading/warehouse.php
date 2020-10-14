<?php

use backend\models\Warehouse;
use common\helper\Component;
use common\helper\Helper;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Kho hàng</h4>
            <div class="action">
                <button
                        data-remote="<?= Url::toRoute(['/bill-lading/form']) ?>"
                        data-toggle="modal"
                        data-target="#modal-warehouse"
                        class="btn-xs btn btn-info">
                    <i class="fe-plus"></i> Thêm kho
                </button>
                <button class="btn-xs btn btn-success"><i class="fe-download"></i> Nhập kho</button>
            </div>
        </div>
        <div class="card-body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'name',
                    [
                        'attribute' => 'status',
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