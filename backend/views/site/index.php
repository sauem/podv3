<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-success color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= $totalOrder ?></h2>
                    <div class="m-b-5">Đơn hàng</div>
                    <i class="ti-shopping-cart widget-stat-icon"></i>
                    <div><i class="fa fa-level-up m-r-5"></i><small>25% higher</small></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-info color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= $totalContact ?></h2>
                    <div class="m-b-5">Liên hệ</div>
                    <i class="ti-bar-chart widget-stat-icon"></i>
                    <div><i class="fa fa-level-up m-r-5"></i><small>17% higher</small></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-warning color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= round($conversionRate, 2) ?>%</h2>
                    <div class="m-b-5">Tỉ lệ chuyển đổi</div>
                    <i class="ti-user widget-stat-icon"></i>
                    <div><i class="fa fa-level-up m-r-5"></i><small>22% higher</small></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-danger color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= \common\helper\Helper::money($totalAmount) ?></h2>
                    <div class="m-b-5">Doanh thu</div>
                    <i class="fa fa-money widget-stat-icon"></i>
                    <div><i class="fa fa-level-down m-r-5"></i><small>-12% Lower</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">
                        Doanh thu theo Sale
                    </h2>
                    <div class="ibox-tools">
                        <select name="sortBySale" class="form-control">
                            <option value="this_week">Tuần này</option>
                            <option value="this_month">Tháng này</option>
                            <option value="last_month">Tháng trước</option>
                        </select>
                    </div>
                </div>
                <div class="ibox-body" id="result-sale">

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">
                        Doanh thu theo marketing
                    </h2>
                    <div class="ibox-tools">
                        <select name="sortByMarketing" class="form-control">
                            <option value="this_week">Tuần này</option>
                            <option value="this_month">Tháng này</option>
                            <option value="last_month">Tháng trước</option>
                        </select>
                    </div>
                </div>
                <div class="ibox-body" id="result-marketing">

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">
                        Doanh thu theo sản phẩm
                    </h2>
                    <div class="ibox-tools">
                        <select name="sortByProduct" class="form-control">
                            <option value="this_week">Tuần này</option>
                            <option value="this_month">Tháng này</option>
                            <option value="last_month">Tháng trước</option>
                        </select>
                    </div>
                </div>
                <div class="ibox-body" id="result-product">

                </div>
            </div>
        </div>
    </div>
    <script id="sale-template" type="text/x-handlebars-template">
        <table class="table table-hover">
            <thead>
            <tr>
                <th colspan="2"><span class="text-warning">{{this.start}} - {{this.end}}</span></th>
            </tr>
            <tr>
                <th>Nhân viên</th>
                <th class="text-right">Doanh thu</th>
            </tr>
            </thead>
            <tbody>

            {{#each this.data}}
            <tr>
                <td>
                    {{user.username}}
                </td>
                <td class="text-right">
                    {{money total}}đ
                </td>
            </tr>
            {{/each}}
            </tbody>
        </table>

    </script>
    <script id="product-template" type="text/x-handlebars-template">
        <table class="table table-hover">
            <thead>
            <tr>
                <th colspan="2"><span class="text-warning">{{this.start}} - {{this.end}}</span></th>
            </tr>
            <tr>
                <th>Sản phẩm/Tên sản phẩm</th>
                <th class="text-right">Doanh thu</th>
            </tr>
            </thead>
            <tbody>

            {{#each this.data}}
            <tr>
                <td>
                    <strong>{{product_sku}}</strong> | {{product.name}}
                </td>
                <td class="text-right">
                    {{money total}}đ
                </td>
            </tr>
            {{/each}}
            </tbody>
        </table>

    </script>
<?php

use yii\helpers\Url;

$urlSale = Url::toRoute(['report/sale']);
$urlMarketing = Url::toRoute(['report/marketing']);
$urlProduct = Url::toRoute(['report/product']);
$js = <<<JS
    initData();
    initData("this_week",  "#result-marketing", "marketing");
     initData("this_week", 'product-template', "#result-product", "product");
    $("select[name='sortBySale']").change(function() {
        let _val = $(this).val();
       initData(_val);
    });
    $("select[name='sortByMarketing']").change(function() {
            let _val = $(this).val();
            initData(_val,  "#result-marketing", "marketing");
    });
    $("select[name='sortByProduct']").change(function() {
            let _val = $(this).val();
            initData(_val, 'product-template', "#result-product", "product");
    });
    
    function initData(_type = "this_week", template = 'sale-template',  result = "#result-sale", role = 'sale') {
        setLoading(result);
        let _url = "";
        switch (role) {
          case "sale":
              _url = "$urlSale";
              break;
              case "marketing":
                  _url = "$urlMarketing";
                  break;
                  case "product":
                      _url = "$urlProduct";
                      break;
        }
      $.ajax({
        url : _url,
        data : {sort : _type},
        type : "POST",
        success : function(res) {
            $(result).html(compileTemplate(template,res))
        }
    });
      function setLoading(el) {
        $(el).html("<tr class='loading'><td colspan='2' class='text-center'><i class='fa fa-spinner fa-spin fa-pulse fa-3x fa-fw'></i></td></tr>");  
      }
      function unsetLoading(el) {
           $(el).find(".loading").remove();
      }
    }
JS;
$this->registerJs($js);