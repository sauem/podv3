<?php

use kartik\daterange\DateRangePicker; ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <h4 class="text-center card-title text-danger">Bộ lọc</h4>
                </div>
                <div class="col-12">
                    <form id="sale-form">
                        <div class="row">
                            <div id="result-filter"></div>
                            <div class="col">
                                <?php
                                echo DateRangePicker::widget([
                                    'name' => 'filter[date]',
                                    'presetDropdown' => true,
                                    'convertFormat' => true,
                                    'includeMonthsFilter' => true,
                                    'pluginOptions' => ['locale' => ['format' => 'd-m-Y']],
                                    'options' => ['class' => 'partner-date', 'placeholder' => 'Chọn ngày tạo đơn']
                                ]);
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12 my-3">
                    <h4 class="text-center card-title text-danger">Chỉ số</h4>
                    <div class="d-flex justify-content-between">
                        <strong class="text-danger">Chú thích</strong>
                        <strong>C3: Tổng số đơn hàng</strong>
                        <strong>C8: Tổng đơn hàng OK</strong>
                        <strong>C11: Tổng đơn hàng chuyển thành công</strong>
                        <strong>C13: Tổng đơn hàng đã chuyển tiền cho đối tác</strong>
                    </div>
                </div>
            </div>
            <div id="chartArea">
                <div class="loading">
                    <div class="spinner-grow text-danger" role="status">
                    </div>
                </div>
                <div class="row" id="result-index">

                </div>
                <div class="row">
                    <div class="col-12">
                        <h4 class="text-center card-title text-danger">Bảng tổng hợp C8 C3</h4>
                    </div>
                    <div class="col-12">

                        <canvas id="index-chart" height="350">

                        </canvas>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <h4 class="text-center card-title text-danger">Bảng tổng hợp C11 C8</h4>
                    </div>
                    <div class="col-12">
                        <canvas id="second-chart" height="350">

                        </canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script id="index-template" type="text/x-handlebars-template">
        <div class="col-3 text-center">
            <p>Doanh thu C8</p>
            <h3>฿{{money revenue_c8}}</h3>
        </div>
        <div class="col text-center">
            <p>C3 (Contacts - Duplicate)</p>
            <h3>{{C3}}</h3>
        </div>
        <div class="col text-center">
            <p>C11 (Đã thanh toán)</p>
            <h3>{{C11}}</h3>
        </div>
        <div class="col-2 text-center">
            <p>C8 (OK)</p>
            <h3>{{C8}}</h3>
        </div>
        <div class="col text-center">
            <p>C8/C3</p>
            <h3>{{C8_C3}}%</h3>
        </div>
        <div class="col text-center">
            <p>C11/C3</p>
            <h3>{{C11_C3}}%</h3>
        </div>
        <div class="col text-center">
            <p>C11/C8</p>
            <h3>{{C11_C8}}%</h3>
        </div>
    </script>
    <script id="filter-template" type="text/x-handlebars-template">
        <div class="col">
            <select title="Marketer"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[marketer][]" class="selectpicker mr-3"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.marketer}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>
        <div class="col">
            <select title="Nguồn contact"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[source][]" class="selectpicker mr-3"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.source}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>
        <div class="col">
            <select title="Sản phẩm"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[product][]" class="selectpicker mr-3"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.product}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>
        <div class="col">
            <select title="Page"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[page][]" class="selectpicker mr-3"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.page}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>
    </script>
<?php

$js = <<<JS
    let html = $('#index-template').html();
    let filterHtml = $('#filter-template').html();
    let template = Handlebars.compile(html);
    let filterTemp = Handlebars.compile(filterHtml);
        
    $(document).ready(function() {
        
        
        getSale('$partner').then(res => {
          const {base,  filter, labels ,calculate, dataSet} = res;
          setLocalStorage('sale',base);
            $("#result-index").html(template(calculate));
            $("#result-filter").replaceWith(filterTemp(filter));
            initSelectPicker();
            initChartIndex(labels, dataSet);
        }).catch(er =>{
           console.log(er); 
        });
    });

    $(document).on('change','.selectpicker, .partner-date', function() {
        
        let base = getLocalStorage('sale');
        if(!base){
            toastr.warning('Dữ liệu chưa cập nhật!');
            return false;
        }
        let searchData = getSearchParams('sale-form',JSON.stringify(base));
       
        getSearch(searchData).then(res => {
            let {base,  filter,labels, calculate, dataSet} = res;
            labels = Object.values(labels);
            switch ('$route') {
              case "sale":
                  firstChart.data.datasets[0].data = Object.values(dataSet.C3);
                  firstChart.data.datasets[1].data = Object.values(dataSet.C8);
                  firstChart.data.datasets[2].data = Object.values(dataSet.C8_C3);
                  firstChart.data.labels = Object.values(labels);
                  firstChart.update();
                  $("#result-index").html(template(calculate));
                  break;
              default:
                  break;
            }
        });
    });


JS;
$this->registerJs($js);