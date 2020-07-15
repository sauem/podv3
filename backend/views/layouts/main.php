<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
        var Action = {
            DELTE: 'del',
            ADD: 'add',
            UP: 'up',
            DOWN: 'down'
        }
    </script>
    <style>
        .ui-widget-content {
            margin-left: 16%;
            margin-top: 23%;
        }

        .grid-view th {
            white-space: nowrap;
        }

        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width: 1200px;
            }
        }
    </style>
</head>
<body class="fixed-navbar">
<?php $this->beginBody() ?>
<div class="page-wrapper">
    <?= $this->render('@backend/views/parts/nav') ?>
    <?= $this->render('@backend/views/parts/sidebar') ?>
    <div class="content-wrapper">


        <div class="page-content fade-in-up">

            <?= $content ?>
        </div>
        <?= $this->render('@backend/views/parts/footer') ?>
    </div>
</div>
<div class="sidenav-backdrop backdrop"></div>
<div class="preloader-backdrop">
    <div class="page-preloader">Loading</div>
</div>

<?php $this->endBody() ?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <script>
        toastr.success("<?= Yii::$app->session->getFlash('success') ?>");
    </script>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <script>
        toastr.warning("<?= Yii::$app->session->getFlash('error') ?>");
    </script>
<?php endif; ?>

</body>
</html>
<?php $this->endPage() ?>
