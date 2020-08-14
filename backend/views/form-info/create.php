<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FormInfo */

$this->title = 'Create Form Info';
$this->params['breadcrumbs'][] = ['label' => 'Form Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
