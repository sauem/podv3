<?php
?>
<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-right mb-0">

            <li class="d-none d-lg-block">
            </li>

            <li class="dropdown d-inline-block d-lg-none">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fe-search noti-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-lg dropdown-menu-right p-0">
                    <form class="p-3">
                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                    </form>
                </div>
            </li>

            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                    <i class="fe-maximize noti-icon"></i>
                </a>
            </li>



            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="/theme2/images/users/user-1.jpg" alt="user-image" class="rounded-circle">
                    <span class="pro-user-name ml-1">
                        <i class="mdi mdi-chevron-down"></i>
                                </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <a href="<?= \yii\helpers\Url::toRoute(['user/view','id' => Yii::$app->user->getId()])?>" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>Tài khoản</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <?php \yii\widgets\ActiveForm::begin([
                        'method' => 'POST',
                        'action' => \yii\helpers\Url::toRoute(['/site/logout'])
                    ]) ?>
                    <button class="dropdown-item notify-item" type="submit" href="#">
                        <i class="fe-log-out"></i> Logout
                    </button>
                    <?php \yii\widgets\ActiveForm::end() ?>

                </div>
            </li>

        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="/" class="logo logo-dark text-center">
                            <span class="logo-sm">
                                <img src="/theme2/images/logto.png" alt="" height="22">
                                <!-- <span class="logo-lg-text-light">UBold</span> -->
                            </span>
                <span class="logo-lg">
                                <img src="/theme2/images/logto.png" alt="" height="20">
                    <!-- <span class="logo-lg-text-light">U</span> -->
                            </span>
            </a>

            <a href="/" class="logo logo-light text-center">
                            <span class="logo-sm">
                                <img src="/theme2/images/logto.png" alt="" height="20">
                            </span>
                <span class="logo-lg">
                                <img src="/theme2/images/gsof.png" alt="" height="45">
                            </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>

        </ul>
        <div class="clearfix"></div>
    </div>
</div>
