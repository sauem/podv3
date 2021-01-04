<?php

use kartik\daterange\DateRangePicker; ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Báo cáo đơn hàng</h4>
            <form id="order-form">
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
        <div class="card-body">
            <table id="result" class="table table-borderless">
                <thead>
                <tr>
                    <th>Ngày ra contact</th>
                    <th>Contact code</th>
                    <th>Tên khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Tình trạng chốt đơn (C8)</th>
                    <th>Tình trạng vận chuyển (C11)</th>
                    <th>Tình trạng chuyển tiền (C13)</th>
                    <th>Doanh thu (C8)</th>
                    <th>Số tiền thực chuyển đối tác(C13)</th>
                </tr>
                </thead>
                <tbody id="order-result">
                <tr>
                    <td class="text-center" colspan="7">
                        <div class="spinner-grow text-danger" role="status"></div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script id="order-template" type="text/x-handlebars-template">
        {{#each this}}
        <tr>
            <td>{{date_register}}</td>
            <td>{{code}}</td>
            <td>{{name}}</td>
            <td>{{phone}}</td>
            <td>{{status}}</td>
            <td>{{status_shipping}}</td>
            <td>{{status_C13}}</td>
            <td>{{money revenue}}</td>
            <td>{{money transfer_C13}}</td>
        </tr>
        {{/each}}
    </script>
    <script id="filter-template" type="text/x-handlebars-template">
        <div class="col">
            <select title="Nguồn contact"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[source][]" class="selectpicker"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.source}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>
        <div class="col">
            <select title="Tình trạng thanh toán C11"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[C11][]" class="selectpicker mr-3"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.C11}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>
        <div class="col">
            <select title="Tình trạng chuyển tiền C13"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[C13][]" class="selectpicker mr-3"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.C13}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>
        <div class="col">
            <select title="Tình trạng chốt đơn C8"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[C8][]" class="selectpicker mr-3"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.C8}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>

    </script>
<?php
$js = <<<JS
    Window.DATA_TABLE = [];
        let html = $("#order-template").html();
        let template = Handlebars.compile(html);
        let filterHtml = $('#filter-template').html();
        let filterTemp = Handlebars.compile(filterHtml);
        let result = null;
        let tableResult = null;
    $(document).ready(function() {
        getOrderDetail("$partner").then(res => {
            if(!res){
                result = "Dữ liệu trống!";
            }
            const { data , filter, base}  = res;
            
            setLocalStorage('order',base);
            
            window.DATA_TABLE = data;
            result = template(data);
            $("#order-result").html(result);
            initDataTable('#result');
            $("#result-filter").replaceWith(filterTemp(filter));
             initSelectPicker();
        }).catch(e =>{
              $("#order-result").html('<tr><td class="text-center" colspan="5">Dữ liệu trống!</td></tr>');
        })
    });
    
    $(document).on('change','.selectpicker, .partner-date', function() {
        let base = getLocalStorage('order');
        if(!base){
            toastr.warning('Dữ liệu chưa cập nhật!');
            return false;
        }
        let searchData = getSearchParams('order-form',JSON.stringify(base),'GetOrder');
        getSearch(searchData).then(res => {
            if($.fn.dataTable.isDataTable('#result')){
                $('#result').DataTable().destroy();
                 $("#order-result").html('');
            }
           const {data } = res;
            window.DATA_TABLE = data;
            result = template(data);
            $("#order-result").html(result);
             initDataTable('#result');
           
        });
    });
JS;
$this->registerJs($js);