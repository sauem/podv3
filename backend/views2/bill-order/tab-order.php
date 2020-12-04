<?php

use kartik\grid\GridView; ?>

<?= GridView::widget([
    'perfectScrollbar' => true,
    'dataProvider' => $orders
]) ?>
