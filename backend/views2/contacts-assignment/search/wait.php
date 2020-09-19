<?php

use kartik\form\ActiveForm;

?>

<div class="collapse mb-3" id="wait-search">
    <?php
    $form = ActiveForm::begin();
    ?>
    <div class="row">
        <div class="col-md-5">
           <div class="input-group">
               <?= $form->field($model, 'code')->textInput(['placeholder' => 'Số điện thoại, tên khách hàng'])->label(false) ?>
              <div class="input-append">
                  <button type="submit" class="btn btn-secondary">Tìm</button>
              </div>
           </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>
