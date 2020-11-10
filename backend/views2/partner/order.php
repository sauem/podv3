<?php

use kartik\daterange\DateRangePicker; ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Báo cáo đơn hàng</h4>
            <div class="row">
                <div id="result-filter"></div>
                <div class="col">
                    <?php
                    echo DateRangePicker::widget([
                        'name' => 'date',
                        'presetDropdown' => true,
                        'convertFormat' => true,
                        'includeMonthsFilter' => true,
                        'pluginOptions' => ['locale' => ['format' => 'm/d/Y']],
                        'options' => ['id' => 'report-date', 'placeholder' => 'Chọn ngày tạo đơn']
                    ]);
                    ?>
                </div>
            </div>
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
                    <th>Doanh thu (C8)</th>
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
            <td>฿{{money revenue}}</td>
        </tr>
        {{/each}}
    </script>
    <script id="filter-template" type="text/x-handlebars-template">
        <div class="col">
            <select title="Số điện thoại"
                    data-actions-box="true"
                    data-live-search="true"
                    name="phone" class="selectpicker"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.phone}}
                <option value="{{this}}">{{this}}</option>
                {{/each}}
            </select>
        </div>
        <div class="col">
            <select title="Tình trạng thanh toán C11"
                    data-actions-box="true"
                    data-live-search="true"
                    name="C11" class="selectpicker"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.C11}}
                <option value="{{this}}">{{this}}</option>
                {{/each}}
            </select>
        </div>
        <div class="col">
            <select title="Tình trạng chốt đơn C8"
                    data-actions-box="true"
                    data-live-search="true"
                    name="C8" class="selectpicker"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.C8}}
                <option value="{{this}}">{{this}}</option>
                {{/each}}
            </select>
        </div>


    </script>
<?php
$js = <<<JS
    Window.DATA_TABLE = [];
    $(document).ready(function() {
        let html = $("#order-template").html();
        let template = Handlebars.compile(html);
         let filterHtml = $('#filter-template').html();
         let filterTemp = Handlebars.compile(filterHtml);
        let result = null;
        let arr  = [];
        getOrderDetail("$partner").then(res => {
            if(!res){
                result = "Dữ liệu trống!";
            }
            const { data , filter}  = res;
            
            data.map((item, key) => {
               arr.push( {
                   code : item[0],
                   date_register  : item[2],
                   name : item[3],
                   phone : item[4],
                   status : item[39],
                   revenue : item[40],
                   status_shipping : item[43],
               });
            });
            window.DATA_TABLE = arr;
            result = template(arr);
            $("#order-result").html(result);
            
            initDataTable('#result');
            $("#result-filter").replaceWith(filterTemp(filter));
             initSelectPicker();
        }).catch(e =>{
              $("#order-result").html('<tr><td class="text-center" colspan="5">Dữ liệu trống!</td></tr>');
        })
    });
    
    $(document).on('change', '.selectpicker', function() {
       let name = $(this).attr('name');
       let val = $(this).val();
       alert(val); 
    });
JS;
$this->registerJs($js);