<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\grid\ActionColumn;
use yii\helpers\Html;
use common\helper\Component;
use yii\helpers\Url;

?>
    <div class="table-responsive">

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
                        return $cog;
                    }
                ],

                [
                    'label' => 'Số điện thoại',
                    'attribute' => 'phone',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $count = sizeof($model->sumContact);
                        return Html::a("$model->phone",
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
        ]) ?>
    </div>
<?php

$js = <<<JS
    
JS;
$this->registerJs($js);