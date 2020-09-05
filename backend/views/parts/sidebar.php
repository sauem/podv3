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
$menu = MenuHelper::getAssignedMenu(Yii::$app->user->id, 2, $callback);
$controller = Yii::$app->controller->id;
$action = Url::toRoute(Yii::$app->controller->getRoute());
$path = explode('/', $action);
$path = array_filter($path);

?>

    <nav class="page-sidebar" id="sidebar">
        <div id="sidebar-collapse">
            <div class="admin-block d-flex">
                <div>
                    <img src="/lib/img/admin-avatar.png" width="45px"/>
                </div>
                <div class="admin-info">
                    <div class="font-strong"><?= Yii::$app->user->getIdentity()->username ?></div>
                </div>
            </div>
            <?php if ($menu && sizeof($menu) > 0) { ?>
                <ul class="side-menu metismenu">
                    <?php
                    foreach ($menu as $item) {
                        $children = isset($item['items']) ? $item['items'] : [];
                        if(Helper::isAdmin() && strpos($item['url'][0],'contact-manager')){
                           continue;
                        }
                        if ($children) {

                            ?>
                            <li>
                                <a href="<?= $item['url'][0] ?>"><i class="sidebar-item-icon <?= $item['icon']?>"></i>
                                    <span class="nav-label">
                                    <?= $item['label'] ?>
                                </span><i class="fa fa-angle-left arrow"></i>
                                </a>
                                <?php if ($children) {
                                    ?>
                                    <ul class="nav-2-level collapse">
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
                                    <?php
                                } ?>
                            </li>
                            <?php
                        } else {
                            ?>
                            <li>

                                <a class="<?= $action == $item['url'][0] ? 'active' : '' ?>"
                                   href="<?= $item['url'][0] ?>"><i class="sidebar-item-icon <?= $item['icon']?>"></i>
                                    <span class="nav-label"><?= $item['label'] ?></span>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    ?>


                </ul>
            <?php } ?>
        </div>
    </nav>
<?php

$js = <<<JS
    $(document).ready(function() {
        let _current_contoller = "$controller";
        $(".side-menu li").each(function(index) {
            let _router = $(this).find("a.active");
            let _path = _router.parent().attr("data-route");
            let _vm = $(this);
            if(typeof _path!=="undefined"){
                _path = JSON.parse(_path);
                _path = Object.entries(_path);
                _path.map(function(item, key) {
                    if(item.includes(_current_contoller)){
                        _vm.addClass("active")
                        _router.closest("ul.nav-2-level").addClass("in")
                        return false;
                    }
                })
                return false;
            }
        })
    })
JS;
$this->registerJs($js);