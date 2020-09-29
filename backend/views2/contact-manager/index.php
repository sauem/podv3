<?php

use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\grid\ActionColumn;
use common\helper\Helper;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\ContactsAssignment;

?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Bảng điểm </h4>
                <div class="w-25">
                    <?php
                    $start = Yii::$app->request->get("start");
                    $end = Yii::$app->request->get("end");
                    $toDate = date('m/d/Y', time()) . ' - ' . date('m/d/Y', time());
                    if ($start !== "NaN" && $end !== "NaN") {
                        $val = date("m/d/Y", $start) . " - " . date("m/d/Y", $end);
                    }
                    echo DateRangePicker::widget([
                        'name' => 'date',
                        'presetDropdown' => true,
                        'value' => isset($val) ? $val : $toDate,
                        'convertFormat' => true,
                        'includeMonthsFilter' => true,
                        'pluginOptions' => ['locale' => ['format' => 'm/d/Y']],
                        'options' => ['placeholder' => 'Chọn ngày tạo đơn']
                    ]);

                    ?>
                </div>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $brankProvider,
                    'responsive' => true,
                    'layout' => "{summary}{items}\n{pager}",
                    'pjax' => true,
                    'pjaxSettings' => [
                        'neverTimeout' => true,
                        'options' => [
                            'id' => 'pjax-brank'
                        ]
                    ],
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'columns' => [
                        [
                            'label' => 'Nhân viên',
                            'attribute' => 'sale',
                        ],
                        [
                            'label' => 'Lead OK',
                            'attribute' => 'ok'
                        ],
                        [
                            'label' => 'Thuê bao',
                            'value' => 'pending'
                        ],
                        [
                            'label' => 'Lead hủy',
                            'value' => 'cancel'
                        ],
                        [
                            'label' => 'Gọi lại',
                            'value' => 'callback'
                        ],

                        [
                            'label' => 'Sai số/ trùng/ bỏ qua',
                            'attribute' => 'failed',
                        ],
                        [
                            'label' => 'CTR',
                            'value' => function ($model) {
                                $ctr = $model['ok'] / ($model['ok'] + $model['cancel']);
                                return round($ctr, 2) . "%";
                            },
                        ],
                        [
                            'label' => 'Điểm',
                            'value' => function ($model) {
                                $point = 0;
                                $point += $model['ok'] * 1;
                                $point += $model['cancel'] * 1;
                                $point += $model['pending'] * 1;
                                $point += $model['callback'] * 0.5;
                                return $point;
                            }
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Số điện thoại gọi lại</h4>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $callbackProvider,
                    'responsive' => true,
                    'tableOptions' => [
                        'id' => 'gridviewData'
                    ],
                    'layout' => "{summary}{items}\n{pager}",
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'pjax' => true,
                    'pjaxSettings' => [
                        'neverTimeout' => true,
                        'options' => [
                            'id' => 'pjax-waiting'
                        ],
                        'enablePushState' => false
                    ],
                    'columns' => [
                        [
                            'label' => 'Số điện thoại',
                            'attribute' => 'phone',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a($model->contact_phone,
                                    Url::toRoute(['view', 'id' => $model->id]), ['data-pjax' => '0']);
                            }
                        ],
                        [
                            'label' => 'Khách hàng',
                            'attribute' => 'phone',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if(!isset($model->contacts[0])){
                                    return null;
                                }
                                return $model->contacts[0]->name;
                            }
                        ],
                        [
                            'label' => 'Trạng thái',
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model) {
                                return ContactsAssignment::label($model->status);
                            }
                        ],
                        [
                            'class' => ActionColumn::class,
                            'template' => '{view}',
                            'header' => 'Hành động',
                            'width' => '120px',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a("<i class='fa fa-eye'></i> chi tiết",
                                        Url::toRoute(['view', 'id' => $model->id]),
                                        ['class' => 'btn btn-sm bg-white', 'data-pjax' => '0']);
                                }
                            ]
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Số điện thoại đang quản lý</h4>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $assignProvider,
                    'responsive' => true,
                    'tableOptions' => [
                        'id' => 'gridviewData'
                    ],
                    'layout' => "{summary}{items}\n{pager}",
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'pjax' => true,
                    'pjaxSettings' => [
                        'neverTimeout' => true,
                        'options' => [
                            'id' => 'pjax-all'
                        ],
                        'enablePushState' => false
                    ],
                    'columns' => [
                        [
                            'label' => 'Số điện thoại',
                            'attribute' => 'phone',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a($model->contact_phone,
                                    Url::toRoute(['view', 'id' => $model->id]), ['data-pjax' => '0']);

                            }
                        ],
                        [
                            'label' => 'Khách hàng',
                            'attribute' => 'phone',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if(!isset($model->contacts[0])){
                                    return null;
                                }
                                return $model->contacts[0]->name;
                            }
                        ],
                        [
                            'label' => 'Trạng thái',
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model) {
                                return ContactsAssignment::label($model->status);
                            }
                        ],
                        [
                            'class' => ActionColumn::class,
                            'template' => '{view}',
                            'header' => 'Hành động',
                            'width' => '120px',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a("<i class='fe-eye'></i> chi tiết",
                                        Url::toRoute(['view', 'id' => $model->id]),
                                        ['class' => 'btn btn-sm bg-white', 'data-pjax' => '0']);
                                }
                            ]
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php
$url = Url::toRoute(Yii::$app->controller->getRoute());
$js = <<<JS
   
    $("body").on("change",'input[name=\'date\']', function() {
        let _val = $(this).val();
        let dates = _val.split(" - ");
        let start = Math.round(new Date(dates[0]).getTime()/1000);
        let end = Math.round(new Date(dates[1]).getTime()/1000);
        
        if(_val !== null || _val !== ""){
             window.location.href = "$url" +'?start='+start + '&end= '+ end;
        }else{
             window.location.href = "$url";
        }
    });
    
JS;
$this->registerJS($js);