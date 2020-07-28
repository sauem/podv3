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
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">#<?= $payment->name ?></h2>
            </div>
            <div class="ibox-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'payment_id')->hiddenInput(['value' => $payment->id]) ?>
                <?= $form->field($model, 'bank_account')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'bank_number')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'bank_address')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'bank_description')->textarea(['rows' => 4]) ?>
                <div class="form-group text-right">
                    <?= Component::reset() ?>
                    <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Thông tin ngân hàng</h2>
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
                        'bank_name',
                        [
                            'attribute' => 'bank_account',
                            'format' => 'html',
                            'value' => function ($model) {
                               return  $model->bank_account."<br>".
                                 $model->bank_number;
                            }
                        ],

                        'bank_address',
                        'bank_description',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit}',
                            'buttons' => [
                                'edit' => function ($url, $model) use( $payment) {
                                    $url = Url::toRoute(['view','id' => $payment->id, 'key' => $model->id]);
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
