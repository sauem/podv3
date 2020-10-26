<?php

use common\helper\Component;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use yii\helpers\Url;
use kartik\form\ActiveForm;

?>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Đơn vị vận chuyển</h4>
            <div class="action">
                <button data-toggle="modal" data-target="#modal-delivery" class="btn btn-sm btn-info">Tạo đơn vị vận
                    chuyển
                </button>
            </div>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'logo',
                        'headerOptions' => [
                            'width' => '80'
                        ],
                        'format' => 'html',
                        'value' => function ($model) {
                            return Html::img($model->logo ? $model->logo : '/theme2/images/delivery.png', [
                                'class' => 'img-fluid img-thumbnail',
                                'width' => 80,
                            ]);
                        }
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'html',
                        'value' => function ($model) {
                            return Html::a($model->name, $model->domain);
                        }
                    ],
                    'phone',
                    'address',
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{update}{delete}',
                        'width' => '20%',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                $url = Url::toRoute(['index', 'id' => $model->id]);
                                return Component::update($url);
                            },
                            'delete' => function ($url, $model) {
                                $url = Url::toRoute(['delete-delivery', 'id' => $model->id]);
                                return Component::delete($url);
                            }
                        ]
                    ]
                ]
            ]) ?>
        </div>
    </div>
    <div id="modal-delivery" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tạo mới đơn vị vận chuyển</h4>
                    <button data-dismiss="modal" class="close">
                        <span>x</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'delivery-form'
                    ]) ?>
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($model, 'name')->label('Tên nhà vận chuyển') ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($model, 'address')->label('Địa chỉ/chi nhánh') ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($model, 'phone')->textInput(['type' => 'number'])->label('Hot line') ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($model, 'domain')->textInput(['type' => 'url'])->label('Website') ?>
                        </div>
                        <div class="col-12 text-right">
                            <?= Component::reset('Hủy') ?>
                            <?= Html::submitButton('<i class="fa fa-save"></i> Lưu', ['class' => 'btn btn-sm btn-success']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
<?php
$js = <<<JS
    let key = (new URL(window.location.href)).searchParams.get('id');
    if(key){
        $('#modal-delivery').modal('show');
    }
JS;
$this->registerJs($js);