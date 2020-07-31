<?php

use yii\helpers\Html;
use backend\models\UserModel;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use common\helper\Component;
use kartik\grid\ActionColumn;
use kartik\form\ActiveForm;
use backend\models\AuthItem;
use backend\models\AuthAssignment;
$this->title = 'User Models';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-md-4">
            <div class="ibox table-responsive">
                <div class="ibox-head">
                    <h2 class="ibox-title">Tạo tài khoản</h2>
                </div>
                <div class="ibox-body">
                    <?php $form = ActiveForm::begin() ?>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <?= $form->field($model, 'username') ?>
                        </div>
                        <div class="form-group col-md-12">
                            <?= $form->field($model, 'email') ?>
                        </div>
                        <?php
                        if ($model->isNewRecord) { ?>
                            <div class="form-group col-12">
                                <?= $form->field($model, 'password_hash') ?>
                            </div>
                        <?php } ?>
                        <div class="form-group col-md-12">
                            <?php
                            $model->role = $model->userRole ? $model->userRole->item_name: null?>
                            <?= $form->field($model, 'role')->dropDownList(
                                \backend\models\AuthItem::Roles(),
                                ['prompt' => 'Chọn quyền quản trị'])->label('Quyền quản trị') ?>
                        </div>
                        <div class="form-group col-md-12">
                            <?= $form->field($model, 'phone_of_day')->textInput(["type" => 'number', 'placeholder' => 'Số điện thoại giới hạn gọi']) ?>
                        </div>
                        <div class="col-md-12 text-right">
                            <?= Component::reset()?>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Danh sách tài khoản</h2>
                    <div class="ibox-tools">
                        <a data-toggle="collapse" href="#filter"><i class="fa fa-filter"></i> Tìm kiếm</a>
                    </div>
                </div>
                <div class="ibox-body">
                    <?= $this->render('_search', ['model' => $searchModel]) ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'responsive' => true,
                        'headerRowOptions' => [
                            'class' => 'thead-light'
                        ],
                        'columns' => [
                            'username',
                            'email:email',
                            'phone_of_day',
                            ['attribute' => 'status', 'format' => 'html', 'value' => function ($model) {
                                return UserModel::label($model->status);
                            }],
                            ['attribute' => 'role', 'format' => 'html', 'value' => function ($model) {
                                return $model->userRole->item_name;
                            }],
                            [
                                'class' => ActionColumn::class,
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model) {
                                        return Component::delete($url);
                                    },
                                    'update' => function ($url, $model) {
                                        $url = \yii\helpers\Url::toRoute(['index','id' => $model->id]);
                                        return Component::update($url);
                                    },
                                ]
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>

    </div>

<?php
