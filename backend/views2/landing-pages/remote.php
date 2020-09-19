<?php

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use backend\models\CategoriesModel;
use backend\models\ProductsModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>
<?php Pjax::begin([
    'enableReplaceState' => false,
    'enablePushState' => false,
    'clientOptions' => [
        'container' => 'p0',
    ]
]); ?>
<?php $form = ActiveForm::begin([
    'id' => 'LandingForm',
    'action' => Url::toRoute(['/contacts-assignment/pending']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,

]) ?>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'name')->label('Tên trang') ?>
                <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->getId()])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'link')->label('Link trang') ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'category_id')
                    ->widget(Select2::className(), ['data' => CategoriesModel::select(),
                        'theme' => Select2::THEME_DEFAULT,
                        'options' => ['prompt' => 'Chọn loại sản phẩm']])
                    ->label("Loại sản phẩm") ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'country')->dropDownList(
                    ArrayHelper::map(Yii::$app->params['country'], 'code', 'name'),
                    ['class' => 'select2']
                ) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'marketer')->textInput(['placeholder' => 'Tên quản lý']) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'product_id')
                    ->widget(Select2::className(), ['data' => ProductsModel::select(),
                        'theme' => Select2::THEME_DEFAULT,
                        'options' => ['prompt' => 'Chọn sản phẩm']])
                    ->label("Sản phẩm") ?>
                <small class="text-warning">#Lời khuyên : Nên chọn sản phẩm với loại sản phẩm tương
                    ứng</small>
            </div>
        </div>
    </div>

<?php ActiveForm::end() ?>
<?php Pjax::end(); ?>