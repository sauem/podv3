<?php

use common\helper\Helper;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use backend\models\ContactsModel;
use kartik\grid\ActionColumn;
use yii\helpers\Url;

?>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Danh sách liên hệ chờ</h4>
            <div class="card-tools">
                <button data-toggle="collapse"
                        data-target="#pending-search"
                        class="btn btn-sm btn-outline-warning">
                    <i class="fe-cloud-off"></i> Tìm kiếm
                </button>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render("search/pending")?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'responsive' => true,
                'layout' => "{summary}{items}\n{pager}",
                'pjax' => true,
                'resizableColumns' => false,
                'pjaxSettings' => [
                    'neverTimeout' => true,
                    'options' => [
                        'id' => 'pjax-waiting'
                    ],
                    'enablePushState' => false
                ],
                'headerRowOptions' => [
                    'class' => 'thead-light'
                ],
                'panelTemplate' => '{panelHeading}{items}{panelFooter}',
                'panel' => [
                    'type' => 'default',
                    'heading' => '<div class="d-flex">' . (Helper::isAdmin() ? Html::a('<i class="fa fa-trash"></i> Xóa lựa chọn', 'javascript:;',
                            [
                                'class' => 'btn btn-xs deleteAll btn-warning',
                                'data-pjax' => '0',
                                'data-model' => $dataProvider->query->modelClass
                            ]) : "") . '</div>',
                ],
                'columns' => [
                    [
                        'class' => CheckboxColumn::class,
                        'checkboxOptions' => function ($model) {
                            $cog['data-phone'] = $model->phone;
                            $cog['data-country'] = $model->country;
                            return $cog;
                        }
                    ],
                    [
                        'label' => 'Code',
                        'format' => 'html',
                        'value' => function ($model) {
                            $html = $model->code . "<br>";
                            $html .= $model->phone;
                            return $html;
                        }
                    ],
                    [
                        'attribute' => 'register_time',
                        'label' => 'Ngày đăng kí',
                        'format' => 'html',
                        'headerOptions' => [
                            'width' => '15%'
                        ],
                        'value' => function ($model) {
                            return date("d/m/Y H:i:s", $model->register_time);
                        }
                    ],
                    [
                        'attribute' => 'option',
                        'label' => 'Yêu cầu',
                        'headerOptions' => [
                            'width' => '15%'
                        ],
                    ],
                    [
                        'label' => 'Trang đích',
                        'headerOptions' => [
                            'width' => '35%'
                        ],
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (!$model->page) {
                                return $model->link;
                            }
                            return Html::tag("p",
                                "<a target='_blank' href='" . \common\helper\Helper::link($model->link) . "' >{$model->page->link}  <i class='fa fa-chrome'></i></a><br>" .
                                "<small class='text-info'>address: <i>{$model->address}</i></small><br>" .
                                "<small class='text-info'>zipcode: <i>{$model->zipcode}</i></small><br>" .
                                "<small class='text-danger'>Note: <i>{$model->note}</i></small><br>"

                            );
                        }
                    ],
                    [
                        'label' => 'Quốc gia',
                        'value' => 'country'
                    ],
                    [
                        'label' => 'Lý do',
                        'attribute' => 'reason_msg',
                    ],
                    [
                        'label' => 'Nội dung',
                        'attribute' => 'reason',
                        'format' => 'html',
                        'value' => function ($model) {
                            return $model->reason;
                        }
                    ],


                    [
                        'class' => ActionColumn::class,
                        'template' => '{link}{option}',
                        'width' => '14%',
                        'buttons' => [
                            'option' => function ($url, $model) {
                                if ($model->reason !== "option") {
                                    return null;
                                }
                                return Html::button("Xác định option", [
                                    'data-pjax' => 0,
                                    'data-toggle' => 'modal',
                                    'data-target' => '#optionModal',
                                    'data-remote' => Url::toRoute([
                                        'form-info/remote',
                                        'option' => $model->option,
                                        'category_id' => $model->page ? $model->page->category->id : null
                                    ]),
                                    'class' => 'btn btn-xs w-100 btn-outline-warning mb-1'
                                ]);
                            },
                            'link' => function ($url, $model) {
                                if (!$model->reason !== "link") {
                                    return null;
                                }
                                return Html::button("Xác định trang", [
                                    'data-pjax' => 0,
                                    'data-toggle' => 'modal',
                                    'data-target' => '#landingModal',
                                    'data-remote' => Url::toRoute([
                                        'landing-pages/remote',
                                        'link' => $model->link,
                                        'country' => $model->country
                                    ]),
                                    'class' => 'btn w-100 btn-xs btn-outline-warning mb-1'
                                ]);
                            },
                        ]
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="modal fade in" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Sửa thông tin liên hệ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="resultRowPending"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="editPendingContact" class="saveRowPending btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="landingModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Xác định trang đích</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border text-success m-2" role="status"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="LandingForm" class="saveRowPending btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade in" id="optionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Xác định yêu cầu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border text-success m-2" role="status"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="infoForm" class="saveRowPending btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
<?php
$js = <<<JS

    initRemote("landingModal");
    initRemote("optionModal");
JS;
$this->registerJs($js);