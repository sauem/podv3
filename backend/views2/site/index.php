<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded bg-soft-primary">
                            <i class="dripicons-wallet font-24 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= $totalOrder ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Đơn hàng</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded bg-soft-primary">
                            <i class="dripicons-wallet font-24 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= $totalContact ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Liên hệ</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>

        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded bg-soft-primary">
                            <i class="dripicons-wallet font-24 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= round($conversionRate, 2) ?>%</span></h3>
                            <p class="text-muted mb-1 text-truncate">Tỉ lệ chuyển đổi</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded bg-soft-primary">
                            <i class="dripicons-wallet font-24 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= $totalAmount?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Liên hệ thành công</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">
                        Doanh thu theo Sale
                    </h4>
                    <div class="card-tools">
                        <select name="sortBySale" class="form-control">
                            <option value="this_week">Tuần này</option>
                            <option value="this_month">Tháng này</option>
                            <option value="last_month">Tháng trước</option>
                        </select>
                    </div>
                </div>
                <div class="card-body" id="result-sale">

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">
                        Doanh thu theo sản phẩm
                    </h4>
                    <div class="card-tools">
                        <select name="sortByProduct" class="form-control">
                            <option value="this_week">Tuần này</option>
                            <option value="this_month">Tháng này</option>
                            <option value="last_month">Tháng trước</option>
                        </select>
                    </div>
                </div>
                <div class="card-body" id="result-product">

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
                    {{money total}}
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
                    {{money total}}
                </td>
            </tr>
            {{/each}}
            </tbody>
        </table>

    </script>
<?php

use yii\helpers\Url;

$urlSale = Url::toRoute(['report/sale']);
$urlProduct = Url::toRoute(['report/product']);
$js = <<<JS
    initData();
     initData("this_week", 'product-template', "#result-product", "product");
    $("select[name='sortBySale']").change(function() {
        let _val = $(this).val();
       initData(_val);
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
        },
        error : function(e) {
          console.log(e.toString())
        }
    })
      function setLoading(el) {
        $(el).html("<tr class='loading'><td colspan='2' class='text-center'><i class='fa fa-spinner fa-spin fa-pulse fa-3x fa-fw'></i></td></tr>");  
      }
      function unsetLoading(el) {
           $(el).find(".loading").remove();
      }
    }
JS;
$this->registerJs($js);