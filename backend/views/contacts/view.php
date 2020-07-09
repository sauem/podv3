<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContactsModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Contacts Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="contacts-model-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'phone',
            'email:email',
            'address:ntext',
            'zipcode',
            'option:ntext',
            'ip',
            'note',
            'link:ntext',
            'short_link:ntext',
            'utm_source',
            'utm_medium',
            'utm_content',
            'utm_term',
            'utm_campaign',
            'host',
            'hashkey',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
