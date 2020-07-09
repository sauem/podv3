<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CategoriesModel */

$this->title = 'Create Categories Model';
$this->params['breadcrumbs'][] = ['label' => 'Categories Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
