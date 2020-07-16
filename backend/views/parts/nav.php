<header class="header">
    <div class="page-brand">
        <a class="link" href="index.html">
                    <span class="brand">Admin
                        <span class="brand-tip">CAST</span>
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
            <li class="dropdown dropdown-inbox">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope-o"></i>
                    <span class="badge badge-primary envelope-badge">9</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-menu-media">
                    <li class="dropdown-menu-header">
                        <div>
                            <span><strong>9 New</strong> Messages</span>
                            <a class="pull-right" href="mailbox.html">view all</a>
                        </div>
                    </li>
                    <li class="list-group list-group-divider scroller" data-height="240px" data-color="#71808f">
                        <div>
                            <a class="list-group-item">
                                <div class="media">
                                    <div class="media-img">
                                        <img src="/lib/img/users/u1.jpg"/>
                                    </div>
                                    <div class="media-body">
                                        <div class="font-strong"></div>
                                        Jeanne Gonzalez<small class="text-muted float-right">Just now</small>
                                        <div class="font-13">Your proposal interested me.</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
            <li class="dropdown dropdown-notification">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell-o rel"><span
                                class="notify-signal"></span></i></a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-menu-media">
                    <li class="dropdown-menu-header">
                        <div>
                            <span><strong>5 New</strong> Notifications</span>
                            <a class="pull-right" href="javascript:;">view all</a>
                        </div>
                    </li>
                    <li class="list-group list-group-divider scroller" data-height="240px" data-color="#71808f">
                        <div>
                            <a class="list-group-item">
                                <div class="media">
                                    <div class="media-img">
                                        <span class="badge badge-success badge-big"><i class="fa fa-check"></i></span>
                                    </div>
                                    <div class="media-body">
                                        <div class="font-13">4 task compiled</div>
                                        <small class="text-muted">22 mins</small></div>
                                </div>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
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
                        <a class="dropdown-item" href="profile.html"><i class="fa fa-user"></i>Profile</a>
                        <a class="dropdown-item" href="profile.html"><i class="fa fa-cog"></i>Settings</a>
                        <a class="dropdown-item" href="javascript:;"><i class="fa fa-support"></i>Support</a>
                        <li class="dropdown-divider"></li>
                        <?php \yii\widgets\ActiveForm::begin([
                            'method' => 'POST',
                            'action' => \yii\helpers\Url::toRoute(['site/logout'])
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