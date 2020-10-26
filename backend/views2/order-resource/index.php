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
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Nguồn đơn hàng</h4>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'responsive' => true,
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'name',
                        'slug',
                        'created_at:datetime',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{delete}{edit}',
                            'buttons' => [
                                'delete' => function ($url, $model) {
                                    return Component::delete($url);
                                },
                                'edit' => function ($url, $model) {
                                    $url = Url::toRoute(['index', 'id' => $model->id]);
                                    return Component::update($url);
                                }
                            ]
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thêm phương thức thanh toán</h4>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <div class="form-group text-right">
                    <?= Component::reset() ?>
                    <?= Html::submitButton('Lưu', ['class' => 'btn btn-sm btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
