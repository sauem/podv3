<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\grid\ActionColumn;
use yii\helpers\Html;
use common\helper\Component;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use common\helper\Helper;

?>
    <div class="table-responsive">
        <?php $fullExport = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'asDropdown' => false,
            'columns' => [
                'code',
                'name',
                'phone',
                'address',
                'zipcode',
                'option',
                'country',
                'ip',
                'note',
                'link',
                'utm_source',
                'utm_medium',
                'utm_content',
                'utm_term',
                'utm_campaign',
                'type',
                'created_at',
                'updated_at'
            ],
            'exportConfig' => [
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_PDF => false,
            ],
        ]); ?>
        <?= $this->render('_search', ['model' => $searchModel]) ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
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
                    'class' => CheckboxColumn::class,
                    'checkboxOptions' => function ($model) {
                        $cog['data-phone'] = $model->phone;
                        $cog['data-country'] = $model->country;
                        return $cog;
                    }
                ],

                [
                    'label' => 'Số điện thoại',
                    'attribute' => 'phone',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a("$model->phone | $model->country",
                            Url::toRoute(['view', 'phone' => $model->phone]), [
                                'data-pjax' => '0'
                            ]);
                    }
                ],
                [
                    'label' => 'Tên khách hàng',
                    'attribute' => 'name',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->name;
                    }
                ],
                [
                    'label' => 'Quản lý',
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function ($model) {
                        if (!$model->assignment) {
                            return null;
                        }
                        return $model->assignment->user->username;
                    }
                ],
                [
                    'label' => 'SL đăng kí',
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function ($model) {
                        $count = sizeof($model->sumContact);
                        return $count;
                    }
                ],
                [
                    'label' => 'Đăng kí cuối',
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function ($model) {
                        return date('d/m/Y H:i:s', $model->latestContact->register_time);
                    }
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a("<i class='fa fa-eye'></i> chi tiết",
                                \yii\helpers\Url::toRoute(['view', 'phone' => $model->phone]),
                                ['class' => 'btn btn-sm bg-white', 'data-pjax' => '0']);
                        }
                    ]
                ],
            ],
            'toolbar' => [
                '{export} {toggleData}'
            ],
            'panel' => [
                'type' => GridView::TYPE_INFO,
                'before' =>
                    (Helper::isAdmin() ? Html::a('<i class="fa fa-trash"></i> Xóa lựa chọn', 'javascript:;',
                        [
                            'class' => 'btn deleteAll btn-warning',
                            'data-pjax' => '0',
                            'data-model' => $dataProvider->query->modelClass
                        ]) : "")
                    . Html::a("<i class='fa fa-file-excel-o'></i> Xuất liên hệ", 'javascript:;', [
                        'class' => 'btn btn-info ml-2 exportAll',
                        'data-pjax' => '0',
                    ])
                ,
            ],
            'toggleDataOptions' => ['minCount' => 10],
            'export' => [
                'itemsAfter' => [
                    '<div role="presentation" class="dropdown-divider"></div>',
                    '<div class="dropdown-header">Export All Data</div>',
                    $fullExport
                ]
            ],
            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        ]) ?>
    </div>
<?php

$js = <<<JS
    $(".exportAll").click(function() {
        $.ajax({
            url : config.exportContactURL,
            type : 'POST',
            data : {},
            cache : false,
            success : function(res) {
                if(res.success){
                    window.location.href = res.file;
                    toastr.success("Đang tải!");
                    return;
                }
                toastr.warning(res.msg);
            }
        })
    })
JS;
$this->registerJs($js);