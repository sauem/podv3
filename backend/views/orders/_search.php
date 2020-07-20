<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrdersSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-model-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'customer_name')
                    ->textInput(['placeholder' => 'Tên khách hàng, email, số điện thoại...'])
                    ->label(false) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'customer_name')
                    ->textInput(['placeholder' => 'Tên khách hàng, email, số điện thoại...'])
                    ->label(false) ?>
            </div>
        </div>
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
