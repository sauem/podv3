<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-model-search collapse" id="filter">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

   <div class="row">
       <div class="col-md-6">
           <?= $form->field($model, 'username')
               ->textInput(['placeholder' => 'Tên, SDT, Email,...'])->label(false) ?>
       </div>
       <div class="col-md-3">
           <?= $form->field($model, 'role')
               ->widget(\kartik\select2\Select2::className(),[
                   'data' => \backend\models\AuthItem::Roles(),
                   'options' => ['prompt' => 'Chọn quyền quản trị']
               ])->label(false) ?>
       </div>
       <div class="col-md-3">
           <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
           <?= \common\helper\Component::reset();?>
       </div>
   </div>

    <?php ActiveForm::end(); ?>

</div>
