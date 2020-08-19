<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use backend\models\ProductsModel;
use backend\models\CategoriesModel;
use kartik\grid\GridView;
use common\helper\Helper;
use yii\helpers\Url;
use common\helper\Component;
use kartik\grid\ActionColumn;

$this->title = 'Form Infos';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-md-5">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Tạo mẫu</h2>
                    <div class="ibox-tools">
                        <button class="btn btn-info btn-sm" data-toggle="modal"
                                data-remote="<?= Url::toRoute(['import']) ?>"
                                data-target="#form-info-import">
                            <i class="fa fa-file-excel-o"></i>
                            Nhập mẫu đơn
                        </button>
                    </div>
                </div>
                <div class="ibox-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'infoForm'
                    ]) ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'category_id')->dropDownList(
                                CategoriesModel::select(),
                                ['class' => 'select2 form-control']
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'revenue')->label('Doanh thu') ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($model, 'content')->textarea(['placeholer' => 'Nội dung']) ?>
                        </div>
                        <?php
                        $skus = $model->skus ? $model->skus : false;
                        ?>
                        <div id="form-sku" class="col-12">
                            <div class="mt-2 item-sku row">
                                <div class="col-md-6">
                                    <?= Html::dropDownList('info[1][sku]', $skus[0] ? $skus[0]->sku : "",
                                        ProductsModel::select("sku", "sku"),
                                        ['class' => 'select2 form-control']
                                    ) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::textInput('info[1][qty]', $skus[0] ? $skus[0]->qty : "", ['required' => true, 'placeholder' => "Số lượng", 'class' => 'form-control']) ?>
                                </div>
                            </div>
                            <div class="mt-2 item-sku row">
                                <div class="col-md-6">
                                    <?= Html::dropDownList('info[2][sku]', isset($skus[1]) ? $skus[1]->sku : "",
                                        ProductsModel::select("sku", "sku"),
                                        ['class' => 'select2 form-control']
                                    ) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::textInput('info[2][qty]', isset($skus[1]) ? $skus[1]->qty : "", ['placeholder' => "Số lượng", 'class' => 'form-control']) ?>
                                </div>
                            </div>
                            <div class="mt-2 item-sku row">
                                <div class="col-md-6">
                                    <?= Html::dropDownList('info[3][sku]', isset($skus[2]) ? $skus[2]->sku : "",
                                        ProductsModel::select("sku", "sku"),
                                        ['class' => 'select2 form-control']
                                    ) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::textInput('info[3][qty]', isset($skus[2]) ? $skus[2]->qty : "", ['placeholder' => "Số lượng", 'class' => 'form-control']) ?>
                                </div>
                            </div>
                            <div class="mt-2 item-sku row">
                                <div class="col-md-6">
                                    <?= Html::dropDownList('info[4][sku]', isset($skus[3]) ? $skus[3]->sku : "",
                                        ProductsModel::select("sku", "sku"),
                                        ['class' => 'select2 form-control']
                                    ) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::textInput('info[4][qty]', isset($skus[3]) ? $skus[3]->qty : "", ['placeholder' => "Số lượng", 'class' => 'form-control']) ?>
                                </div>
                            </div>
                            <div class="mt-2 item-sku row">
                                <div class="col-md-6">
                                    <?= Html::dropDownList('info[5][sku]', isset($skus[4]) ? $skus[4]->sku : "",
                                        ProductsModel::select("sku", "sku"),
                                        ['class' => 'select2 form-control']
                                    ) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::textInput('info[5][qty]', isset($skus[4]) ? $skus[4]->qty : "", ['placeholder' => "Số lượng", 'class' => 'form-control']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class=" mt-4 text-right">
                                <?php if ($model->isNewRecord && !$model->content) { ?>
                                    <?= Html::resetButton("Làm mơi", ['class' => 'btn btn-warning']) ?>
                                <?php } else { ?>
                                    <?= Component::reset('Hủy') ?>
                                <?php } ?>
                                <?= Html::submitButton("Lưu", ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Danh sách mẫu</h2>
                </div>
                <div class="ibox-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'pjax' => true,
                        'pjaxSettings' => [
                            'neverTimeout' => true,
                            'options' => [
                                'id' => 'pjax-info'
                            ]
                        ],
                        'columns' => [
                            [
                                'label' => 'Loại sản phẩm',
                                'attribute' => 'category_id',
                                'value' => function ($model) {
                                    return $model->category->name;
                                }
                            ],
                            [
                                'label' => 'Doanh thu',
                                'attribute' => 'trvenue',
                                'value' => function ($model) {
                                    return Helper::money($model->revenue);
                                }
                            ],
                            [
                                'label' => 'Nội dung',
                                'headerOptions' => [
                                    'width' => "35%"
                                ],
                                'attribute' => 'content',
                                'value' => function ($model) {
                                    return $model->content;
                                }
                            ],
                            [
                                'label' => 'Sku',
                                'attribute' => 'content',
                                'format' => 'html',
                                'value' => function ($model) {
                                    $skus = $model->skus;
                                    $html = "";
                                    if ($skus) {
                                        foreach ($skus as $ku) {
                                            $html .= "$ku->qty*$ku->sku<br>";
                                        }
                                    }
                                    return $html;
                                }
                            ],
                            [
                                "class" => ActionColumn::className(),
                                "template" => "{update}{delete}",
                                "buttons" => [
                                    "update" => function ($url, $model) {
                                        $url = Url::toRoute(['index', 'id' => $model->id]);
                                        return Component::update($url);
                                    },
                                    "delete" => function ($url, $model) {
                                        return Component::delete($url);
                                    }
                                ]
                            ],
                        ]
                    ]) ?>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Mẫu cần cập nhật</h2>
                    <div class="ibox-tools">
                        <button id="exportInfoWait" class="btn-outline-danger btn btn-sm"><i class="fa fa-file-excel-o"></i> Xuất dữ liệu</button>
                    </div>
                </div>
                <div class="ibox-body">
                    <?= GridView::widget([
                        'dataProvider' => $optionProvider,
                        'pjax' => true,
                        'layout' => "{items}{pager}",
                        'rowOptions' => function ($model) {

                            if ($model->formInfo['content'] == $model->option) {
                                return ['style' => "display :none"];
                            }
                        },
                        'pjaxSettings' => [
                            'neverTimeout' => true,
                            'options' => [
                                'id' => 'pjax-wait-info'
                            ]
                        ],
                        'columns' => [
                            [
                                'label' => 'Danh mục',
                                'attribute' => 'content',
                                'value' => function ($model) {
                                    return "--";
                                }
                            ],
                            [
                                'label' => 'Doanh thu',
                                'attribute' => 'content',
                                'value' => function ($model) {
                                    return "--";
                                }
                            ],
                            [
                                'label' => 'Nội dung',
                                'headerOptions' => [
                                    'width' => "35%"
                                ],
                                'attribute' => 'content',
                                'value' => function ($model) {
                                    return $model->option;
                                }
                            ],
                            [
                                'label' => 'Sku',
                                'attribute' => 'content',
                                'value' => function ($model) {
                                    return "--";
                                }
                            ],
                            [
                                "class" => ActionColumn::className(),
                                "template" => "{update}",
                                "buttons" => [
                                    "update" => function ($url, $model) {
                                        $url = Url::toRoute(['index', 'content' => $model->option]);
                                        return Component::update($url);
                                    },
                                ]
                            ],
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="form-info-import" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nhập mẫu form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <a class="text-warning" href="<?= \yii\helpers\Url::toRoute(['/file/order_example.xlsx']) ?>"><i
                                class="fa fa-download"></i> File dữ liệu mẫu</a>
                    <div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="button" data-action="order" class="btn handleData btn-primary">Nhập sản phẩm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$js = <<<JS
    initRemote("form-info-import");
    
    window.INFO =  {
        count : 1,
        infos : []    
    }
    $(document).on("beforeSubmit","#infoForm",function(event) {
        event.preventDefault();
        let _form = new FormData($(this)[0]);
        let _infoID = "$model->id";
        _form.append("info_id",_infoID);
        $.ajax({
            url : config.saveFormInfo,
            type : "POST",
            processData: false,
            contentType : false,
            data  : _form,
            success : function(res) {
                if(res.success){
                    toastr.success(res.msg);
                    __reloadData();
                    setTimeout(function() {
                       window.location.replace("/form-info/index");
                    },1500);
                   return false;
                }
                toastr.warning(res.msg);
            }
        })
        return false;
        
    })
JS;
$this->registerJs($js);