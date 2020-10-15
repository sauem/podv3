<?php

use backend\models\ProductsModel;
use common\helper\Helper;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                #<?= $model->id ?> <?= $model->name ?>
            </h4>
            <div class="actions">
                <button data-toggle="modal"
                        data-target="#modal-product"
                        class="btn btn-info btn-sm">Nhập sản phẩm
                </button>
                <button data-toggle="modal"
                        data-target="#modal-import"
                        class="btn btn-warning btn-sm">Nhập excel
                </button>
            </div>
        </div>
        <div class="card-body">
            <?=
            GridView::widget([
                'dataProvider' => $productStorage,
                'tableOptions' => [
                    'class' => 'table-sm'
                ],
                'columns' => [
                    'product.name',
                    'product.sku',
                    'quantity:html:Tồn kho',
                    [
                        'label' => 'Ngày nhập kho',
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return date('d/m/Y', $model->created_at);
                        }
                    ],
                    [
                        'class' => ActionColumn::class,
                        'template' => '{export}{import}',
                        'width' => '20%',
                        'buttons' => [
                            'export' => function ($url, $model) {
                                return Html::a('<i class="fe-download"></i> Nhập', 'javascript:;', [
                                    'class' => 'btn btn-xs changeQuantity mx-1 btn-outline-success',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modal-quantity',
                                    'data-key' => $model->product_id,
                                    'data-pjax' => '0'
                                ]);
                            },
                            'import' => function ($url, $model) {
                                return Html::a('<i class="fe-upload"></i> Xuất', 'javascript:;', [
                                    'class' => 'btn btn-xs changeQuantity mx-1 btn-outline-warning',
                                    'data-pjax' => '0',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modal-quantity',
                                    'data-key' => $model->product_id,
                                ]);
                            },
                        ]
                    ]
                ]
            ])
            ?>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="modal-product">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm sản phẩm - <?= $model->name ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin(['id' => 'storageForm']) ?>
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($storage, 'warehouse_id')->hiddenInput(['value' => $model->id])->label(false) ?>
                            <?= $form->field($storage, 'product_id')
                                ->widget(Select2::className(), ['data' => ProductsModel::select(),
                                    'theme' => Select2::THEME_DEFAULT])->label('Sản phẩm') ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($storage, 'quantity')
                                ->textInput(['type' => 'number', 'min' => 1])
                                ->label('Số lượng') ?>
                        </div>
                        <div class="col-12 text-right">
                            <button data-dismiss="modal" class="btn btn-sm btn-secondary">
                                <i class="fe-x"></i> Đóng
                            </button>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fe-save"></i> Lưu
                            </button>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="modal-quantity">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nhập/Xuất số lượng kho</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Số lượng</label>
                        <input class="form-control" name="quantity" value="0" min="0" type="number">
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea class="form-control" name="note"></textarea>
                    </div>
                    <div class="text-right">
                        <button data-dismiss="modal" class="btn btn-sm btn-secondary">
                            <i class="fe-x"></i> Đóng
                        </button>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fe-save"></i> Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$js = <<<JS
    $(document).on('beforeSubmit','#storageForm', function(event) {
        event.preventDefault();
        swal.fire({
            title : 'Đang thực hiện....',
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen : () => {
                swal.showLoading();
                $.ajax({
                    url : '/bill-lading/save-storage',
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data : new FormData($(this)[0]),
                    cache : false,
                    success : res => {
                        swal.close();
                        if(res){
                            toastr.success('Thêm kho hàng thành công!');
                            $('#modal-product').modal('hide');
                            __reloadData();
                        }
                        
                    },
                });
            }
        })
        return false;
    })
JS;
$this->registerJs($js);