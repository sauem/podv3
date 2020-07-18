<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\UserModel */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-md-6">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Chi tiết tài khoản</h2>
            </div>
            <div class="ibox-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'username',
                        'email:email',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>

                <?php
                    $form = \kartik\form\ActiveForm::begin()
                ?>
                    <?= $form->field($model,'password_hash')->label("Nhập mật khẩu mới")?>
                    <?= Html::submitButton("Lưu",['class' => 'btn btn-success'])?>
                <?php
                    \kartik\form\ActiveForm::end()
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Lịch sử tài khoản</h2>
            </div>

        </div>
    </div>
</div>
