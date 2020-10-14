<?php

use backend\models\Warehouse;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

?>

<?php
Pjax::begin();
$form = ActiveForm::begin([
    'options' => [
        'data-pjax' => true
    ]
]) ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'name')->label('Tên kho') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'country')->dropDownList(
                ArrayHelper::map(Yii::$app->params['country'], 'code', 'name'),
                ['class' => 'select2']
            )->label('Thị trường') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'status')->dropDownList(
                Warehouse::STATUS
            )->label('Trạng thái') ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'note')->textarea(['rows' => 3])->label('Ghi chú') ?>
        </div>
    </div>
    <div class="col-12 text-right">
        <button type="button" data-dismiss="modal" class="btn btn-secondary btn-sm">
            <i class="fe-x"></i> Đóng
        </button>
        <button type="submit" class="btn btn-success btn-sm">
            <i class="fe-save"></i> <?= !$model->isNewRecord ? 'Cập nhật' : 'Lưu'?>
        </button>
    </div>
<?php
ActiveForm::end();
Pjax::end();
?>

<?php
$js = <<<JS
    initSelect2();
JS;
$this->registerJs($js);
?>