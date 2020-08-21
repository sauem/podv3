<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ZipcodeCountry */

$this->title = 'Update Zipcode Country: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Zipcode Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="zipcode-country-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
