<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ContactsAssignmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="contacts-assignment-search collapse <?= Yii::$app->request->get('ContactsSearchModel') ? 'show' : '' ?>"
         id="filter1">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                "id" => "search",
                'data-pjax' => 1
            ],
        ]); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'name')->textInput(['placeholder' => 'Tìm tên, code, SĐT,...'])->label(false) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'assign')->dropDownList([
                    'Trạng thái' => [
                        'approved' => 'Đã phân bổ',
                        'pending' => 'Hàng chờ'
                    ]
                ], ['prompt' => 'Trạng thái xử lý'])->label(false) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'user_id')->dropDownList([
                    'Sales' => \backend\models\UserModel::listSales()
                ], ['prompt' => 'Sale phụ trách', 'class' => 'select2', 'multiple' => true])->label(false) ?>
            </div>
            <div class="col-md-3">
                <?= \common\helper\Component::reset() ?>
                <?= Html::submitButton('Tìm', ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
<?php

$this->registerJs('$("#search").on("beforeSubmit", function (e) {

});', \yii\web\View::POS_READY);