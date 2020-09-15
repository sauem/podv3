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
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'resizableColumns' => false,
            'tableOptions' => [
                'id' => 'gridviewData'
            ],
            'layout' => "{items}\n{pager}",
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
                    'headerOptions' => [
                        'width' => '15%'
                    ],
                    'format' => 'raw',
                    'value' => function ($model) {
                        $count = sizeof($model->sumContact);
                        return Html::a("$model->phone | $model->country ($count) ",
                            Url::toRoute(['view', 'phone' => $model->phone]), [
                                'data-pjax' => '0'
                            ]);
                    }
                ],
                [
                    'label' => 'Tên khách hàng',
                    'attribute' => 'name',
                    'headerOptions' => [
                        'width' => '40%'
                    ],
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->name;
                    }
                ],
                [
                    'label' => 'Quản lý',
                    'attribute' => 'status',
                    'format' => 'html',
                    'headerOptions' => [
                        'width' => '15%'
                    ],
                    'value' => function ($model) {
                        if (!$model->assignment) {
                            return null;
                        }
                        return $model->assignment->user->username;
                    }
                ],
                [
                    'label' => 'Đăng kí cuối',
                    'attribute' => 'status',
                    'headerOptions' => [
                        'width' => '15%'
                    ],
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
                                Url::toRoute(['view', 'phone' => $model->phone]),
                                ['class' => 'btn btn-xs btn-outline-info', 'data-pjax' => '0']);
                        }
                    ]
                ],
            ],
            'panelTemplate' => '{panelHeading}{items}{panelFooter}',
            'panel' => [
                'type' => 'default',
                'heading' => '<div class="d-flex">'.(Helper::isAdmin() ? Html::a('<i class="fa fa-trash"></i> Xóa lựa chọn', 'javascript:;',
                        [
                            'class' => 'btn btn-xs deleteAll btn-warning',
                            'data-pjax' => '0',
                            'data-model' => $dataProvider->query->modelClass
                        ]) : "")
                    . Html::a("<i class='fe-download-cloud'></i> Xuất liên hệ", 'javascript:;', [
                        'class' => 'btn btn-xs btn-info ml-2 exportAll',
                        'data-pjax' => '0',
                    ]) .'{export}{toggleData}' . '</div>' ,
            ],
            'toggleDataContainer' => ['class' => 'btn-group-sm ml-1'],
            'exportContainer' => ['class' => 'btn-group-sm ml-1']

        ]) ?>

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