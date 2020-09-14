<?php

use yii\bootstrap4\Nav;
use mdm\admin\components\MenuHelper;
use common\helper\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$callback = function ($menu) {
    //var_dump($menu);
    return [
        'label' => $menu['name'],
        'url' => [$menu['route']],
        'icon' => $menu['data'],
        'options' => [
            'class' => 'w-100',
        ],
        'items' => $menu['children']
    ];
};
$root = 2;
if (Helper::userRole(\backend\models\UserModel::_PARTNER)) {
    $root = 23;
}

$menu = MenuHelper::getAssignedMenu(Yii::$app->user->id, $root, $callback);
$controller = Yii::$app->controller->id;
$action = Url::toRoute(Yii::$app->controller->getRoute());
$path = explode('/', $action);
$path = array_filter($path);
?>

<div class="left-side-menu">

    <div class="h-100" data-simplebar>
        <div id="sidebar-menu">
            <ul id="side-menu">
                <?php if ($menu && sizeof($menu) > 0) { ?>
                    <?php
                    foreach ($menu as $key => $item) {
                        $children = isset($item['items']) ? $item['items'] : [];
                        if (Helper::isAdmin() && strpos($item['url'][0], 'contact-manager')) {
                            continue;
                        }
                        if ($children) {
                            ?>

                            <li>
                                <a href="#parent_<?= $key?>" data-toggle="collapse">
                                    <i class="<?= $item['icon'] ?>"></i>
                                    <span> <?= $item['label'] ?> </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <?php if ($children) {
                                    ?>
                                    <div class="collapse" id="parent_<?= $key?>">
                                        <ul class="nav-second-level">
                                            <?php
                                            foreach ($children as $child) {
                                                ?>
                                                <li data-route='<?= json_encode($path) ?>'>
                                                    <a class="<?= $action == $child['url'][0] ? 'active' : '' ?>"
                                                       href="<?= $child['url'][0] ?>"><?= $child['label'] ?></a>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <?php
                                } ?>

                            </li>

                            <?php
                        } else {
                            ?>
                            <li>
                                <a href="<?= $item['url'][0] ?>"
                                   class="<?= $action == $item['url'][0] ? 'active' : '' ?>">
                                    <i class="<?= $item['icon'] ?>"></i>
                                    <span> <?= $item['label'] ?> </span>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                <?php } ?>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
