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
<div class="ibox">
    <div class="ibox-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'register_time:datetime',
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
            ],
        ]) ?>

    </div>
</div>