<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-model-search collapse <?= Yii::$app->request->get("UserSearchModel") ? "show" : ""?>" id="filter">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

   <div class="row">
       <div class="col-md-5">
           <?= $form->field($model, 'username')
               ->textInput(['placeholder' => 'Tên, SDT, Email,...'])->label(false) ?>
       </div>
       <div class="col-md-4">
           <?= $form->field($model, 'role')
               ->widget(\kartik\select2\Select2::className(),[
                   'data' => \backend\models\AuthItem::Roles(),
                   'theme' => \kartik\select2\Select2::THEME_CLASSIC,
                   'options' => [
                       'prompt' => 'Chọn qản trị',
                       'multiple' => true
                   ]
               ])->label(false) ?>
       </div>
       <div class="col-md-3">
           <?= Html::submitButton('Tìm', ['class' => 'btn btn-primary']) ?>
           <?= \common\helper\Component::reset();?>
       </div>
   </div>

    <?php ActiveForm::end(); ?>

</div>
