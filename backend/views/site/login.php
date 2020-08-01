<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="box">
        <input type="checkbox" id="toggle" class="box__toggle" hidden>
        <img src="https://source.unsplash.com/zv3ckJKftC4/300x400" alt="Picture by Autumn Studio" class="box__image">
        <form class="form form--register" action="">
        </form>

        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'form form--login'
            ]
        ]); ?>
        <h1 class="form__title">Tcom.asia</h1>
        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form__input'])->label('Tài khoản') ?>
        <?= $form->field($model, 'password')->passwordInput(['class' => 'form__input'])->label('Mật khẩu') ?>
        <button type="submit" class="form__button">Đăng nhập</button>
        <?php ActiveForm::end(); ?>
    </div>
</div>
