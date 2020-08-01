<header class="header">
    <div class="page-brand">
        <a class="link" href="index.html">
                    <span class="brand">
                        <span class="brand-tip">LEADS</span>
                    </span>
            <span class="brand-mini">AC</span>
        </a>
    </div>
    <div class="flexbox flex-1">
        <!-- START TOP-LEFT TOOLBAR-->
        <ul class="nav navbar-toolbar">
            <li>
                <a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="ti-menu"></i></a>
            </li>
            <li>
                <form class="navbar-search" action="javascript:;">
                    <div class="rel">
                        <span class="search-icon"><i class="ti-search"></i></span>
                        <input class="form-control" placeholder="Search here...">
                    </div>
                </form>
            </li>
        </ul>
        <!-- END TOP-LEFT TOOLBAR-->
        <!-- START TOP-RIGHT TOOLBAR-->
        <ul class="nav navbar-toolbar">

            <li class="dropdown dropdown-user">
                <?php if (Yii::$app->user->isGuest) {
                    ?>
                    <a class="nav-link">
                        <img src="/lib/img/admin-avatar.png"/>
                        <span></span>Guest<i class="fa fa-angle-down m-l-5"></i></a>
                    <?php
                } else { ?>
                    <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                        <img src="/lib/img/admin-avatar.png"/>
                        <span></span><?= Yii::$app->user->getIdentity()->username?><i class="fa fa-angle-down m-l-5"></i></a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?= \yii\helpers\Url::toRoute(['/user/view','id' => Yii::$app->user->getId()])?>"><i class="fa fa-user"></i>Tài khoản</a>
                        <li class="dropdown-divider"></li>
                        <?php \yii\widgets\ActiveForm::begin([
                            'method' => 'POST',
                            'action' => \yii\helpers\Url::toRoute(['/site/logout'])
                        ]) ?>
                        <button class="dropdown-item" type="submit" href="#"><i class="fa fa-power-off"></i>Logout
                        </button>
                        <?php \yii\widgets\ActiveForm::end() ?>
                    </ul>
                <?php } ?>
            </li>
        </ul>
        <!-- END TOP-RIGHT TOOLBAR-->
    </div>
</header>