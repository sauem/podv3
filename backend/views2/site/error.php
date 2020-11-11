<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<style>
    #container {
        height: 100vh;
        background-image: url('/theme2/images/bg3.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background-blend-mode: multiply;
        background-color: rgba(0,0,0,0.5);
    }

    #container img {
        position: absolute;
        left: 0;
        right: 0;
        top: 20%;
        margin: auto;
    }
    #container h1{
        color: white;
        text-align: center;
    }
</style>
<div class="site-error">
    <div id="container">
        <img src="/theme2/images/gsof.png" width="200"/>
        <h1>ĐỐI TÁC TIN CẬY CÙNG BẠN<br>
            MỞ RỘNG THỊ TRƯỜNG ĐÔNG NAM Á</h1>
    </div>
</div>
