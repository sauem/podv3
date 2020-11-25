<?php

use backend\models\ProductsModel;
use common\helper\Helper;
use yii\helpers\Url;
use common\helper\Component;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use backend\models\WarehouseTransaction;
use kartik\select2\Select2;
use yii\helpers\Html; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                <a href="<?= Url::toRoute(['warehouse'])?>"><i class="fe-arrow-left"></i> Quản ký kho</a>  | #<?= $model->id ?> <?= $model->name ?>
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
                    'class' => 'table-sm',
                ],
                'pjaxSettings' => [
                    'neverTimeout' => true,
                    'options' => [
                        'id' => 'pjax-warehouse'
                    ]
                ],
                'columns' => [
                    'po_code',
                    [
                        'attribute' => 'product',
                        'format' => 'html',
                        'label' => 'Sản phẩm',
                        'value' => function ($model) {
                            $html = $model->product->name . '<br>';
                            $html .= "{$model->product->category->name} | <strong>{$model->product_sku}</strong>";
                            return $html;
                        }
                    ],
                    [
                        'attribute' => 'quantity',
                        'format' => 'html',
                        'label' => 'Số lượng nhập',
                        'value' => function ($model) {
                            return Helper::money($model->quantity, 0, ',', '.');
                        }
                    ],
                    [
                        'attribute' => 'unit_pirce',
                        'format' => 'html',
                        'label' => 'Giá vốn/đơn vị',
                        'value' => function ($model) {
                            return Helper::money($model->unit_price / $model->quantity, 0) . 'đ';
                        }
                    ],
                    [
                        'attribute' => 'unit_pirce',
                        'format' => 'html',
                        'label' => 'Giá vốn',
                        'value' => function ($model) {
                            return Helper::money($model->unit_price, 0) . 'đ';
                        }
                    ],
                    [
                        'class' => ActionColumn::class,
                        'template' => '{action}{delete}',
                        'width' => '20%',
                        'buttons' => [
                            'action' => function ($url, $model) {
                                return Html::a('<i class="fe-download"></i> Nhập', 'javascript:;', [
                                    'class' => 'btn btn-xs changeQuantity mx-1 btn-outline-success',
                                    'data-product' => $model->product_sku,
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modal-quantity',
                                    'data-pjax' => '0'
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                $url = Url::toRoute(['warehouse-storage-delete', 'id' => $model->id]);
                                return Component::delete($url);
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
                        <div class="col-md-6">
                            <?= $form->field($storage, 'po_code')
                                ->textInput(['placeholder' => '#PO-AUTO', 'disabled' => true])->label('Mã nhập') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($storage, 'product_sku')
                                ->widget(Select2::className(), ['data' => ProductsModel::select('sku', 'name'),
                                    'theme' => Select2::THEME_DEFAULT])->label('Sản phẩm') ?>
                            <?= $form->field($storage, 'warehouse_id')->hiddenInput(['value' => $model->id])->label(false) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($storage, 'quantity')
                                ->textInput(['type' => 'number', 'min' => 1])
                                ->label('Số lượng') ?>
                        </div>
                        <div class="col-md-6">
                            <?= Component::money($form, $storage, 'unit_price')->label('Giá vốn') ?>
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
                    <?php ActiveForm::begin(['id' => 'transactionForm']) ?>
                    <input type="hidden" name="product_sku">
                    <input type="hidden" name="warehouse_id" value="<?= $model->id ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số lượng</label>
                                <input class="form-control" name="quantity" value="0" min="0" type="number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <?php
                                $data = WarehouseTransaction::TRANSACTION_TYPE;
                                unset($data[5]);
                                unset($data[6]);
                                echo Select2::widget([
                                    'theme' => Select2::THEME_DEFAULT,
                                    'data' => $data,
                                    'name' => 'trans_type',
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <textarea class="form-control" name="note"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button data-dismiss="modal" class="btn btn-sm btn-secondary">
                            <i class="fe-x"></i> Đóng
                        </button>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fe-save"></i> Lưu
                        </button>
                    </div>
                    <?php ActiveForm::end() ?>
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
            onBeforeOpen :async () => {
                swal.showLoading();
                try {
                  await $.ajax({
                    url : AJAX_ENDPOINT.saveStorage,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data : new FormData($(this)[0]),
                    cache : false,
                    success : res => toastr.success('Thêm kho hàng thành công!')
                    });
                }catch (e) {
                    toastr.error(e.responseJSON.message);
                }finally {
                   swal.close();
                   $('#modal-product').modal('hide');
                   //window.location.reload();
                }
            }
        })
        return false;
    });
    $('#modal-quantity').on('shown.bs.modal', function (e) {
        let btn = $(e.relatedTarget);
        let product = $(btn).data('product');
        $('#transactionForm').find('input[name="product_sku"]').val(product);
    });
    $(document).on('beforeSubmit','#transactionForm',function(event) {
        event.preventDefault();
        swal.fire({
            title : 'Đang thực hiện...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen : async () => {
                try {
                    await $.ajax({
                        url : AJAX_ENDPOINT.saveStorageTrans,
                        contentType: false,
                        processData: false,
                        cache: false,
                        data : new FormData($(this)[0]),
                        type: 'POST',
                        success : res =>{
                            console.log(res);
                            toastr.success('Thao tác thành công!');
                        }
                    });
                } catch (e) {
                    toastr.error(e.responseJSON.message);
                }finally {
                   swal.close();
                   $('#modal-quantity').modal('hide');
                    //window.location.reload();
                }
            }
        })
        return false;
    });
JS;
$this->registerJs($js);