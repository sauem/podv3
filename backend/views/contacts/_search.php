<?php

use backend\models\ContactsSearchModel;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ContactsSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="landing-pages-search mt-3 collapse <?= Yii::$app->request->get('ContactsSearchModel') ? 'show' : ''?>" id="filter">

<?php $form = ActiveForm::begin([
        'action' => ['index','phone' => Yii::$app->request->get('phone')],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model,'name')->textInput(['placeholder' => 'Tên sản phẩm, mã sản phẩm,..'])->label(false)?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model,'status')->dropDownList(
                    \backend\models\ContactsModel::STATUS,
                    ['prompt' => 'Trạng thái liên hệ']
            )->label(false)?>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?= \common\helper\Component::reset() ?>
                <?= Html::submitButton('Tìm kiếm', ['class' => 'btn btn-outline-secondary']) ?>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
