<?php

use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\helper\Component;
use backend\models\UserModel;
use backend\models\OrdersModel;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use backend\models\Payment;
use kartik\grid\ActionColumn;
use common\helper\Helper;


$this->title = 'Orders Models';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('modal/_collapse_order') ?>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Danh sách đơn hàng</h4>
        </div>
        <div class="card-body">
            <?php $fullExportMenu = ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'asDropdown' => false,
                'columns' => [
                    'code',
                    'customer_name',
                    'customer_phone',
                    'customer_email',
                    'address',
                    'city',
                    'district',
                    'zipcode',
                    'country',
                    'total',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            if ($model->status == OrdersModel::_PENDING) {
                                return "OK";
                            }
                            return $model->status;
                        }
                    ],
                    [
                        'label' => 'Sale',
                        'attribute' => 'user_id',
                        'value' => function ($model) {
                            return $model->user->username;
                        }
                    ],
                    [
                        'label' => 'Maketer',
                        'attribute' => 'user_id',
                        'value' => function ($model) {
                            if (!isset($model->contact->contact->page)) {
                                return null;
                            }
                            return $model->contact->contact->page->marketer;
                        }
                    ],
                    'order_note',
                    'vendor_note',
                    [
                        'label' => 'PTTT',
                        'attribute' => 'payment_method',
                        'value' => function ($model) {
                            return $model->payment ? $model->payment->name : "";
                        }
                    ],
                    [
                        'label' => 'Hóa đơn chuyển khoản',
                        'value' => function ($model) {
                            $bills = $model->billings;
                            $html = "";
                            if ($bills) {
                                foreach ($bills as $k => $bill) {
                                    $url = Url::toRoute("/file/$bill->path", 'http');
                                    $html .= "=HYPERLINK(\"$url\",\"Hóa đơn\")\n";
                                }
                            }
                            return $html;
                        }
                    ],
                    [
                        'label' => 'Giá vận chuyển',
                        'attribute' => 'shipping_price',
                        'value' => function ($model) {
                            return $model->shipping_price ? Helper::formatExcel($model->shipping_price) : "";
                        }
                    ],
                    [
                        'label' => 'Loại SP',
                        'value' => function ($model) {
                            if (isset($model->contact->contact->page)) {
                                return $model->contact->contact->page->category->name;
                            }
                            return null;
                        }
                    ],
                    [
                        'label' => 'Sku',
                        'value' => function ($model) {
                            $items = $model->items;
                            $html = "";
                            if ($items) {
                                foreach ($items as $item) {
                                    $html .= $item->product_sku . ",";
                                }
                            }
                            return substr($html, 0, -1);
                        }
                    ],
                    [
                        'label' => 'Số lượng',
                        'value' => function ($model) {
                            $items = $model->items;
                            $html = "";
                            if ($items) {
                                foreach ($items as $item) {
                                    $html .= $item->qty . ",";
                                }
                            }
                            return substr($html, 0, -1);
                        }
                    ],
                    [
                        'label' => 'Tổng hợp',
                        'value' => function ($model) {
                            $items = $model->items;
                            $html = "";
                            if ($items) {
                                foreach ($items as $item) {
                                    $html .= $item->qty . "*" . $item->product_sku . ",";
                                }
                            }
                            return substr($html, 0, -1);
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return date('d/m/Y', $model->created_at);
                        }
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => function ($model) {
                            return date('d/m/Y', $model->updated_at);
                        }
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_PDF => false,
                ],
            ]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'responsive' => true,
                'resizableColumns' => false,
                'tableOptions' => [
                    'id' => 'gridviewData'
                ],
                'layout' => "{items}\n{pager}",
                'headerRowOptions' => [
                    'class' => 'thead-light'
                ],
                'pjax' => true,
                'pjaxSettings' => [
                    'neverTimeout' => true,
                    'options' => [
                        'id' => 'pjax-orders'
                    ],
                    'enablePushState' => false
                ],
                'columns' => [
                    [
                        'class' => CheckboxColumn::class,
                    ],
                    [
                        'attribute' => 'customer_name',
                        'format' => 'html',
                        'footer' => '<strong>Tổng </strong>',
                        'value' => function ($model) {
                            $html = "<a data-pjax='0' href='" . Url::toRoute(['view', 'id' => $model->id]) . "'>{$model->customer_name}</a><br>";
                            $html .= "<a href='" . Url::toRoute(['view', 'id' => $model->id]) . "' class='badge-info badge'>{$model->code}</a><br>";
                            $html .= $model->customer_phone . "<br>";
                            $html .= $model->customer_email . '<br>';
                            return $html;
                        }
                    ],
                    [
                        'label' => 'Địa chỉ',
                        'attribute' => 'address',
                        'format' => 'html',
                        'value' => function ($model) {
                            $html = $model->address . "<br>";
                            $html .= $model->district . "<br>";
                            $html .= $model->city . "<br>";
                            $html .= Helper::getCountry($model->country);
                            return $html;
                        }
                    ],
                    [
                        'label' => 'Trạng thái',
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return OrdersModel::statusLabel($model->status);
                        }
                    ],
                    [
                        'label' => 'Người tạo đơn',
                        'attribute' => 'total',
                        'format' => 'html',
                        'value' => function ($model) {
                            if (!$model->user) {
                                return null;
                            }
                            return $model->user->username;
                        }
                    ],
                    ['label' => 'sản phẩm', 'attribute' => 'customer_phone',
                        'format' => 'raw', 'value' => function ($model) {
                        $html = '';
                        foreach ($model->items as $item) {
                            $html .= "<span class='badge mb-1 badge-default'>{$item->product->sku} | {$item->product->name} | x{$item->qty}</span><br>";
                        }
                        return $html;
                    }],
                    [
                        'label' => 'Tổng đơn',
                        'attribute' => 'total',
                        'format' => 'html',
                        'footer' => Helper::money($dataProvider->query->sum('total')),
                        'value' => function ($model) {
                            return Helper::money($model->total);
                        }
                    ],
                    [
                        'label' => 'Ngày tạo đơn',
                        'attribute' => 'created_at',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $create = Html::tag("span",
                                "<i class='fa fa-plus'></i> | " . date('H:i:s d/m/Y', $model->created_at),
                                [
                                    'class' => 'badge badge-default ',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'giờ tạo đơn'
                                ]);
                            $canEdit = Html::tag("span", "<i class='fa fa-lock'></i> | " . date('H:i:s d/m/Y', $model->block_time),
                                [
                                    'class' => 'badge badge-default mt-2',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Giờ khóa đơn'
                                ]);
                            if ($model->block_time == 0) {
                                $canEdit = Html::tag("span", "<i class='fa fa-lock'></i> | đã khóa chỉnh sửa", [
                                    'class' => 'badge badge-default mt-2',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Trạng thái khóa'
                                ]);
                            }
                            return "$create<br>$canEdit";
                        }
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'width' => '150px',
                        'header' => 'Hành động',
                        'template' => '{update}{block}{status}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                if ($model->hasLocked()) {
                                    return null;
                                }
                                return Html::a("<i class='fa fa-edit'></i> sửa đơn", 'javascript:;', [
                                    'class' => 'btn showOrderForm btn-sm btn-info mt-2',
                                    'data-pjax' => '0',
                                    //'data-toggle' => 'collapse',
                                    //'data-target' => '#collapse-order',
                                    'data-key' => $model->id,
                                ]);
                            },
                            'export' => function ($url, $model) {
                                return Html::a("<i class='fa fa-cloud-download'></i> Xuất đơn", "javascript:;", [
                                    'class' => 'bg-white export btn btn-sm mt-2',
                                    'data-key' => $model->id,
                                    'data-pjax' => '0'
                                ]);
                            },
                            'block' => function ($url, $model) {
                                if (!Helper::userRole(UserModel::_ADMIN)) {
                                    return null;
                                }
                                $button = Html::a("<i class='fa fa-lock'></i> Khóa sửa", 'javascript:;', [
                                    'class' => 'btn-danger block btn btn-sm mt-2',
                                    'data-key' => $model->id,
                                    'data-pjax' => '0'
                                ]);
                                if ($model->hasLocked()) {
                                    $button = Html::a("<i class='fa fa-lock'></i> Mở khóa", 'javascript:;', [
                                        'class' => 'btn-success block btn btn-sm mt-2',
                                        'data-key' => $model->id,
                                        'data-type' => 'open',
                                        'data-pjax' => '0'
                                    ]);
                                }
                                return $button;
                            },
                            'status' => function ($url, $model) {
                                return Html::a("<i class='fa fa-bar-chart'></i> Trạng thái", "javascript:;", [
                                    'class' => 'btn-warning changeStatus btn btn-sm mt-2',
                                    'data-key' => $model->id,
                                    'data-pjax' => '0'
                                ]);
                            }
                        ]
                    ],
                ],
                'panelTemplate' => '{panelHeading}{items}{panelFooter}',
                'panel' => [
                    'type' => 'default',
                    'heading' => '<div class="d-flex">' .
                        Html::a("<i class='fe-shopping-cart'></i> Tạo đơn", "javascript:;", [
                            'class' => 'mr-1 btn btn-outline-success btn-sm showOrderForm',
                            'data-pjax' => '0'])
                        . (Helper::isAdmin() ? Html::a('<i class="fe-trash"></i> Xóa lựa chọn', 'javascript:;',
                            [
                                'class' => 'btn deleteAll btn-outline-warning  btn-sm',
                                'data-pjax' => '0',
                                'data-model' => $dataProvider->query->modelClass
                            ]) : "")
                        . '{export}{toggleData}' . '</div>',
                ],
                'export' => [
                    'itemsAfter' => [
                        '<div role="presentation" class="dropdown-divider"></div>',
                        '<div class="dropdown-header">Export All Data</div>',
                        '<div class="p-2">' . $fullExportMenu . '</div>'
                    ]
                ],
                'toggleDataContainer' => ['class' => 'btn-group-sm ml-1'],
                'exportContainer' => ['class' => 'btn-group-sm ml-1']

            ]) ?>
        </div>
    </div>

<?php
$js = <<<JS
        window.ORDER = {
            isCreated : 0,
            skus : [],
            option : "",
            cate : null,
            formInfos : [],
            products : [],
            billings : [],
            total : 0,
            subTotal : 0,
            shipping : 0
        }
        
    let collapse = $("#collapse-order");
    let body = $("body");
    
     body.on("click",".removeItem",function() {
      __removeItem(this);
    });
    body.on('click','#addProduct',function() {
        __addProduct(this); 
     });
    body.on("change",".money",function() {
        __moneyChange(this);
    });
    body.on("change","input[name='shipping_price']",function() {
        let _val = $(this).val();
        ORDER.shipping = typeof _val == "undefined" ? 0 : _val;
        __reloadTotal();
    }); 
    
    body.on("click",".changeStatus",function() {
        const _key = $(this).data('key');
         const { value: fruit } =  Swal.fire({
              title: 'Lự chọn trạng thái',
              input: 'select',
              inputOptions: {
                'pending': 'Đợi duyệt',
                'cancel': 'Hủy đơn',
              },
              inputPlaceholder: 'Lự chọn trạng thái',
              showCancelButton: true,
              inputValidator: (value) => {
                    $.ajax({
                        url : config.changeOrderStatus,
                        cache :false,
                        type : 'POST',
                        data : { status : value, key : _key},
                        success : function(res) {
                            if(res.success){
                                toastr.success("Thay đổi trạng thái thành công!");
                                __reloadData();
                            }else{
                                toastr.warning(res.msg);
                            }
                        }
                    })
              }
            })
    });
    body.on("click",".showOrderForm",function() {
       collapse.collapse("toggle");
       let key = $(this).data("key");
       
       try {
            showCardLoading('#collapse-order');
            loadOrder(key).then(res => {
              hideCardLoading('#collapse-order');
              const {customer} = res;
              ORDER.total = customer.info.total;
              ORDER.subTotal = customer.info.sub_total;
              
              if(res.isCreated){
                    ORDER.isCreated = 1;
                    res.customer.info.isCreated = 1;
                }else{
                    ORDER.isCreated = 0;
                    res.customer.info.isCreated = 0;
                }
               __complieTemplate(res);
         });
       }catch (e) {
            toastr.warning(e.message);
       }
    });
    collapse.on("show.bs.collapse", function(e) {
        $("html, body").animate({ scrollTop: 0 }, "slow");
            let _val = $("select[name='payment_method']").val();
            let _bill_image = $(".bill-image");
            switch (_val) {
                case "9999":
                    _bill_image.css({"display" : "block"});
                    if((ORDER.billings).length <= 0){
                        _bill_image.find("input[type='file']").attr("required",true);
                    }
                    break;
                default:
                    _bill_image.css({"display" : "none"});
                    _bill_image.find("input[type='file']").attr("required",false);
                    break;
            }
            if(ORDER.isCreated === 1){
                collapse.find(".card-title.top").text( "Tạo đơn hàng mới!");
            }else{
                 collapse.find(".card-title.top").text("Chỉnh sửa đơn hàng!"); 
            }
            
        restOrder();
    });
    collapse.on("hidden.bs.collapse", function(e) {
         $("#resultProduct").empty();
        $("#resultItemProduct").empty();
        $("#resutlTotal").empty();
        if(ORDER.billings.length > 0){
                _removeImage();
            }
        restOrder();
    });
    
    const loadOrder = async (_key) => {
        return  $.ajax({
            url : config.orderData,
            cache : false,
            type : 'POST',
            data : { key : _key},
            });   
    }
    const __removeItem = _this  => {
        swal.fire({
                    title : 'Cảnh báo',
                    icon : "error",
                    text  : 'Loại bỏ sản phẩm này?',
                    showCancelButton : true
                }).then(val =>{
                    if(val.value){
                        $(_this).closest(".form-group").remove();
                        let _sku = $(_this).data("sku");
                        if(ORDER.skus.includes(_sku)){
                          ORDER.skus = ORDER.skus.filter(item => item !== _sku);
                          ORDER.products = ORDER.products.filter( pro => pro.sku !== _sku);
                          __reloadTotal();
                        }
                    }
                });
    }
     const __complieTemplate = data => {
        const {customer, items , skus} = data;
        $("#resultInfo").html(compileTemplate('template-customer', customer));
        $("#resultSku").html(compileTemplate("template-sku", skus));
       console.log(data);
       
        ORDER.shipping = customer.info.shipping_price;
        ORDER.billings = customer.path;
        if(items.length > 0){
             $.each(items, function(index, item) {
                
                 let _item =  __addItemProduct(item.product, item.price, item.qty);
                 if(!ORDER.skus.includes(_item.sku)){
                     ORDER.skus.push(_item.sku);
                 }
                 $("#resultItemProduct").append(compileTemplate("template-item-product", _item));
            });
        };
    }
    const __addProduct = (_this) => {
             let _sku = $(_this).closest(".input-group").find("select > option:selected").val();
                $.ajax({
                    url : config.ajaxProduct,
                    cache : false,
                    async  : false,
                    type :'POST',
                    data : {sku : _sku},
                    success : function(res) {
                         if(ORDER.skus.includes(_sku)){
                             toastr.warning("Sản phẩm " + _sku + " đã tồn tại trong đơn hàng!");
                             return;
                         };
                        ORDER.skus.push(_sku);
                        let _item =  __addItemProduct(res.product);
                        $("#resultItemProduct").prepend(compileTemplate("template-item-product", _item));
                        __reloadTotal();
                    }
                });
        }
        
    const __moneyChange = (_this) =>{
      let _sku = $(_this).data("sku");
      let _val = $(_this).val();
      __changeProductPrice(_sku,_val);
    }
    
    $(document).on("beforeSubmit", "#formOrder",function(res) {
      res.preventDefault();
        let _formData = new FormData($(this)[0]);
        let _action = $(this).attr("action");
        _formData.append("bills" , ORDER.billings);
        $.ajax({
           url : _action,
           type : "POST",
           processData : false,
           contentType :false,
           data : _formData,
           success : function(res) {
                
                if(res.success){
                    toastr.success("Tạo đơn hàng thành công!");
                    collapse.toggle();
                    restOrder();
                    __reloadData();
                    return;
                }
                toastr.warning(res);
           }
        })
      return false;
    });
JS;

$this->registerJs($js);

