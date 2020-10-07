<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use common\helper\Helper;
use backend\models\ContactsModel;
use kartik\grid\ActionColumn;

?>

    <div class="box-header d-flex justify-content-between">
        <h4 class="card-title">Tất cả liên hệ</h4>
        <div class="toolbar">
            <div class="btn-group">
                <button type="button" data-toggle="collapse" data-target="#all-search"
                        class="btn mr-1 btn-xs btn-outline-success"><i
                            class="fe-search"></i> Tìm kiếm
                </button>

                <button type="button" class="btn mr-1 btn-xs btn-info approveContact"><i
                            class="fe-bar-chart"></i> Hủy liên hệ được chọn
                </button>
                <button data-model="<?= $dataProvider->query->modelClass ?>" type="button"
                        class="btn btn-xs mr-1 btn-danger deleteAll"><i
                            class="fe-trash"></i> Xoá liên hệ được chọn
                </button>
                <button data-model="<?= $dataProvider->query->modelClass ?>" type="button"
                        class="btn btn-xs btn-warning updateSheet"><i
                            class="fe-cloud"></i> cập nhật G.sheet
                </button>
            </div>
        </div>
    </div>
    <div class="box-body">
        <div class="mb-4">
            <?= $this->render("../search/all") ?>
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'layout' => "{summary}{items}\n{pager}",
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options' => [
                    'id' => 'pjax-all'
                ]
            ],
            'headerRowOptions' => [
                'class' => 'thead-light'
            ],
            'columns' => [
                [
                    'class' => CheckboxColumn::class,
                    'checkboxOptions' => function ($model) {
                        if (!$model->page) {
                            return ['data-cate' => null];
                        }
                        return ['data-cate' => $model->page->category_id];
                    }
                ],
                [
                    'label' => 'Contact code',
                    'attribute' => 'code',
                    'format' => 'html',
                    'value' => function ($model) {
                        $html = $model->code . "<br>";
                        $html .= $model->phone . "<br>";
                        $html .= ContactsModel::label($model->status);
                        return $html;
                    }
                ],
                [
                    'label' => 'Ngày đặt hàng',
                    'attribute' => 'register_time',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::tag("p", date("d/m/Y H:i:s", $model->register_time));
                    }
                ],
                [
                    'label' => 'Trang đích',
                    'attribute' => 'link',
                    'headerOptions' => [
                        'width' => '15%'
                    ],
                    'format' => 'raw',
                    'value' => function ($model) {
                        $html = null;
                        if ($model->page) {
                            $html .= "<a target='_blank' href='" . Helper::link($model->link) . "' >{$model->page->link}  <i class='fa fa-chrome'></i></a><br>";
                            $html .= "<small class='text-warning'>Địa chỉ: <i>{$model->address}</i></small><br>";
                            $html .= "<small class='text-warning'>Zipcode: <i>{$model->zipcode}</i></small>";
                        }
                        return $html;
                    }
                ],
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
                            "<br><small>{$model->page->product->sku} </small> | <small><i>{$model->page->category->name}</i></small>");
                    }
                ],
                [
                    'label' => 'Yêu cầu của khách',
                    'attribute' => 'option',
                    'headerOptions' => [
                        'width' => '15%'
                    ]
                ],
                [
                    'label' => 'Ghi chú của khách',
                    'attribute' => 'note',
                ],
                [
                    'class' => ActionColumn::className(),
                    'header' => 'Thao tác',
                    'width' => '140px',
                    'template' => '{cancel}{edit}',
                    'buttons' => [
                        'cancel' => function ($url, $model) {
                            return Html::button("<i class='fe-phone-missed'></i> Hủy", [
                                'class' => 'btn btn-sm cancelButton mt-1 btn-outline-warning w-100',
                                'data-toggle' => 'tooltip',
                                'data-key' => $model->id,
                                'data-phone' => $model->phone,
                                'title' => 'Khách hủy',
                                'data-pjax' => '0'
                            ]);
                        },
                        'edit' => function ($url, $model) {
                            return Html::button("<i class='fe-edit'></i> Sửa", [
                                'class' => 'btn btn-sm  mt-1 btn-outline-info w-100',
                                'data-toggle' => 'modal',
                                'data-target' => '#modalDetail',
                                'data-remote' => \yii\helpers\Url::toRoute(['']),
                                'data-pjax' => '0'
                            ]);
                        },

                    ]
                ],
            ],
        ]) ?>
    </div>
<?php

$js = <<<JS
$(".approveContact").click(function(){
     let _keys = $('.grid-view').yiiGridView('getSelectedRows');
            if(_keys.length <= 0){
                swal.fire({
                    title : "Thông báo",
                    text : "Chọn liên hệ cần thay đổi trạng thái!",
                    icon : "error",
                });
                return;
            }
            
            swal.fire({
                title : "Thông báo!",
                icon : "info",
                text : "Thay đổi trạng thái cái liên hệ được chọn sang trạng thái khách hủy?",
                showCancelButton : true
            }).then( val => {
                if(val.value){
                    swal.fire({
                        title : "Đang xử lý...",
                        icon : "info",
                        onBeforeOpen : () => {
                            swal.showLoading();
                            $.ajax({
                                    url : config.changeMultipleStatus,
                                    type : "POST",
                                    data : { keys : _keys},
                                    cache :false,
                                    success : function(res) {
                                        if(res.success){
                                            toastr.success(res.msg);
                                            __reloadData();
                                        }else{
                                            swal.fire("Lỗi!", res.msg);
                                        }
                                           swal.close();
                                    }
                                })
                        }
                    })
                }
            });
            
            
});
$(".updateSheet").click(function() {
    swal.fire({
        title : 'Đang thực hiện',
        closeOnClickOutside : false,
        onBeforeOpen : () => {
            swal.showLoading();
            try {
              $.ajax({
                url : config.pushGoogleSheet,
                data : {},
                type : "POST",
                cache : false,
                success : res => {
                    console.log(res)
                    swal.close();
                    console.log(res)
                }
              });
            }catch (e) {
                toastr.warning(e.message)
            }
        }
    })
});

JS;
$this->registerJs($js);
