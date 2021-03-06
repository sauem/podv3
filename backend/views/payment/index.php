<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use common\helper\Component;
use yii\helpers\Url;
$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-5">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Thêm phương thức thanh toán</h2>
            </div>
            <div class="ibox-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>

                <div class="form-group text-right">
                    <?= Component::reset() ?>
                    <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Phuơng thức thanh toán</h2>
            </div>
            <div class="ibox-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'responsive' => true,
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'name',
                        'description',
                        'created_at:datetime',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}{edit}',
                            'buttons' => [
                                'view' => function ($url) {
                                    return Component::view($url);
                                },
                                'edit' => function ($url, $model) {
                                    $url = Url::toRoute(['index','id' => $model->id]);
                                    return Component::update($url);
                                }
                            ]
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
