<?php

use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'method' => 'GET',
    'action' => ['index']
]) ?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'action')->textInput([
            'placeholder' => 'Hành động, chức năng, tài khoản....'
        ])->label(false) ?>
    </div>
    <div class="col-md-3">
        <?= \common\helper\Component::reset() ?>
        <?= \yii\helpers\Html::submitButton("<i class='fe-search'></i> Tìm kiếm",['class' => 'btn btn-outline-success btn-sm']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

