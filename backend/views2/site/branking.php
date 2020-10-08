<?php

use backend\models\ContactsLog;
use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use yii\helpers\Url;

?>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Bảng điểm SALE</h4>
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
                'dataProvider' => $dataProvider,
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
                            if($model['ok'] <= 0){
                                return 0;
                            }
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
