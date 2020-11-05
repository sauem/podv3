<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'G-sof';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container">
    <div class="box" style="width: fit-content; color: white; background-color: rgba(0,0,0,0.8)">
       
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'form form--login'
            ]
        ]); ?>
        <img src="/theme2/images/gsof.png" width="80%">
        <p class="form__description">Đồng hành cùng bạn mở rộng kinh doanh tại Đông Nam Á</p>
        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form__input'])->label('Tài khoản') ?>
        <?= $form->field($model, 'password')->passwordInput(['class' => 'form__input'])->label('Mật khẩu') ?>
        <button type="submit" class="form__button">Đăng nhập</button>
        <?php ActiveForm::end(); ?>
    </div>
</div>
