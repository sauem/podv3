<?php

use kartik\grid\ActionColumn;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url; ?>

    <div class="row">
        <div class="col-12">
            <button id="createOrder" class="btn btn-success btn-sm mb-2"><i class="fe-archive"></i> Tạo đơn
            </button>
        </div>
    </div>

<?= GridView::widget([
    'perfectScrollbar' => true,
    'dataProvider' => $orders,
    'layout' => '{items}{pager}',
    'options' => [
        'id' => 'vendor'
    ],
    'tableOptions' => [
        'class' => 'table-sm',
    ],
    'columns' => [
        [
            'class' => CheckboxColumn::class,
            'checkboxOptions' => function ($model) {
                return ['value' => $model->code];
            }
        ],
        [
            'attribute' => 'code',
            'format' => 'html',
            'value' => function ($model) {
                return Html::a($model->code, '');
            }
        ],
        'customer_name',
        'customer_phone',
        [
            'label' => 'Kho',
            'value' => function ($model) {
                return $model->code;
            }
        ],
        [
            'label' => 'Trạng thái kho',
            'value' => function ($model) {
                return $model->code;
            }
        ],
        [
            'header' => '',
            'class' => ActionColumn::class,
            'template' => '{createOrder}',
            'width' => '10%',
            'buttons' => [
                'createOrder' => function ($url, $model) {
                    $url = Url::toRoute(['bill-order/create', 'code' => $model->code]);
                    return Html::a('<i class="fe-archive"></i> Tạo đơn', $url, [
                        'class' => 'btn btn-sm btn-success',
                        'data-code' => $model->code
                    ]);
                }
            ]

        ]
    ]
]) ?>
<?php
$js = <<<JS
    $('#createOrder').on('click',function() {
          let  keys = $('#vendor').yiiGridView('getSelectedRows');
          alert(keys);
    });
JS;
$this->registerJs($js);
