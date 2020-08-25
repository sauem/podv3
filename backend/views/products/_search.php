<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductsSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="landing-pages-search collapse <?= Yii::$app->request->get('ProductsSearchModel') ? 'show' : ''?>" id="filter">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Tên sản phẩm,mã sản phẩm,...'])->label(false) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'category_id')->widget(\kartik\select2\Select2::className(),[
                'data' => \backend\models\CategoriesModel::select(),
                'options' => [
                    'prompt' => 'loại sản phẩm sản phẩm'
                ]
            ])->label(false) ?>
        </div>
        <div class="col-md-4">
            <?= Html::submitButton('Tìm kiếm', ['class' => 'btn btn-primary']) ?>
            <?= \common\helper\Component::reset()?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

