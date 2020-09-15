<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\helper\Helper;
use kartik\grid\ActionColumn;
use common\helper\Component;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use backend\models\LandingPages;
use yii\helpers\ArrayHelper;
$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'username',
                        'email',
                        'pic',
                        [
                            'class' => ActionColumn::class,
                            'width' => '200px',
                            'template' => '{update}{delete}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    $url = Url::toRoute(['partner', 'id' => $model->id]);
                                    return Component::update($url);
                                },
                                'delete' => function ($url, $model) {
                                    $url = Url::toRoute(['user/delete', 'id' => $model->id]);
                                    return Component::delete($url);
                                }
                            ]
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tạo khách hàng</h4>
                <hr>
                <?php $form = ActiveForm::begin(); ?>

                <div class="row">
                    <div class="form-group col-md-12">
                        <?= $form->field($model, 'username') ?>
                    </div>
                    <div class="form-group col-md-12">
                        <?= $form->field($model, 'email') ?>
                        <?= $form->field($model, 'is_partner')->hiddenInput(['value' => 1])->label(false) ?>
                        <?= $form->field($model, 'role')->hiddenInput(['value' => 'Partner'])->label(false) ?>
                        <?= $form->field($model, 'phone_of_day')->hiddenInput(['value' => 0])->label(false) ?>
                    </div>
                    <?php
                    if ($model->isNewRecord) { ?>
                        <div class="form-group col-12">
                            <?= $form->field($model, 'password_hash') ?>
                        </div>
                    <?php } ?>
                    <div class="form-group col-12">
                        <?php
                        if (!$model->isNewRecord) {
                            $model->page_id = ArrayHelper::getColumn($model->clientPages, 'page_id');
                        }
                        ?>
                        <?= $form->field($model, 'page_id[]')->widget(Select2::className(), [
                            'data' => LandingPages::selectOption(),
                            'theme' => Select2::THEME_DEFAULT,
                            'options' => [
                                'value' => $model->page_id,
                                'multiple' => true
                            ]
                        ]) ?>
                    </div>
                </div>

                <div class="form-group">
                    <small class="text-danger"> * các trường bắt buộc</small>

                    <?= Component::reset('Hủy') ?>
                    <?= Html::submitButton('<i class="fe-save"></i> Lưu', ['class' => 'btn btn-sm btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>