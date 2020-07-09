<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LandingPages */

$this->title = 'Create Landing Pages';
$this->params['breadcrumbs'][] = ['label' => 'Landing Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="landing-pages-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
