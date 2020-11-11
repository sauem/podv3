<?php

use kartik\daterange\DateRangePicker; ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Báo cáo tài chính</h4>
        </div>
        <div class="card-body">
            <h4 class="text-center card-title text-danger">Bộ lọc</h4>
            <form id="finance-form">
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

                <div class="col-12">
                    <div class="row" id="result-calculate">

                    </div>
                </div>
            </div>
            <h4 class="text-center mt-4">BẢNG TỔNG HỢP C8 C11</h4>
            <canvas id="finance-top" style="width: 100%; height: 350px;">

            </canvas>

            <h4 class="text-center mt-4">BẢNG TỔNG HỢP CONTACT</h4>
            <canvas id="finance-bottom" style="width: 100%; height: 350px;">

            </canvas>
        </div>
    </div>
    <script id="finance-template" type="text/x-handlebars-template">
        <div class="col-1 text-center">
            <p>C11/C8</p>
            <h4>{{C11_C8}}%</h4>
            <hr>
            <p>C13/C11</p>
            <h4>{{C13_C11}}%</h4>
        </div>
        <div class="col-11 text-center">
            <div class="row">
                <div class="col">
                    <p>Doanh thu C13</p>
                    <h4>฿{{money totalC13Trans}}</h4>
                </div>
                <div class="col">
                    <p>Doanh thu C11</p>
                    <h4>฿{{money C11}}</h4>
                </div>
                <div class="col">
                    <p>Phí dịch vụ (18%)</p>
                    <h4>฿{{money total_dv}}</h4>
                </div>
                <div class="col">
                    <p>Phí thu hộ</p>
                    <h4>฿{{money total_thu_ho}}</h4>
                </div>
                <div class="col">
                    <p>Phí vận chuyển</p>
                    <h4>฿{{money total_vch}}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p>Doanh thu C13 đã đối soát</p>
                    <h4>฿{{money tien_da_dx}}</h4>
                </div>
                <div class="col">
                    <p>Doanh thu C11 đã đối soát</p>
                    <h4>฿{{money C13}}</h4>
                </div>
                <div class="col">
                    <p>Phí dịch vụ đã đối soát</p>
                    <h4>฿{{money dv_da_dx}}</h4>
                </div>
                <div class="col">
                    <p>Phí thu hộ đã đối soáṭ</p>
                    <h4>฿{{money thu_ho_da_dx}}</h4>
                </div>
                <div class="col">
                    <p>Phí vận chuyển đã đối soát</p>
                    <h4>฿{{money vch_da_dx}}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p>Doanh thu C13 chưa đối soát</p>
                    <h4>฿{{money tien_chua_dx}}</h4>
                </div>
                <div class="col">
                    <p>Doanh thu C11 chưa đối soát</p>
                    <h4>฿{{money C13_chua_dx}}</h4>
                </div>
                <div class="col">
                    <p>Phí dịch vụ chưa đối soát</p>
                    <h4>฿{{money dv_chua_dx}}</h4>
                </div>
                <div class="col">
                    <p>Phí thu hộ chưa đối soát</p>
                    <h4>฿{{money thu_ho_chua_dx}}</h4>
                </div>
                <div class="col">
                    <p>Phí vận chuyển chưa đối soát</p>
                    <h4>฿{{money vch_chua_dx}}</h4>
                </div>
            </div>
        </div>
    </script>
    <script id="filter-template" type="text/x-handlebars-template">
        <div class="col">
            <select title="Tình trạng chốt đơn C8"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[statusC8][]" class="selectpicker mr-3"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.statusC8}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>
        <div class="col">
            <select title="Tình trạng chuyển tiền (C13)"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[statusC13][]" class="selectpicker mr-3"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.statusC13}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>
        <div class="col">
            <select title="Tình trạng thanh toán (C11)"
                    data-actions-box="true"
                    data-live-search="true"
                    name="filter[statusC11][]" class="selectpicker mr-3"
                    multiple data-selected-text-format="count"
                    data-style="btn-light">
                {{#each this.statusC11}}
                <option value="{{this}}">{{#if this}} {{this}} {{else}} NULL {{/if}}</option>
                {{/each}}
            </select>
        </div>
    </script>
<?php

$js = <<<JS
      
        let html = $("#finance-template").html();
        let temp = Handlebars.compile(html);
        let filter = $("#filter-template").html();
        let filterTemp =  Handlebars.compile(filter);
    $(document).ready(function() {
       
        getFinance('$partner').then(res =>{
         const {calculate, base, dataSet, labels, filter} = res;
         let dataTop = {
             column_1 : dataSet.C8,
             column_2 : dataSet.C11
         }
         let dataBottom = {
             column_1 : dataSet.C11,
             column_2 : dataSet.C13
         }
         
         setLocalStorage('finance', base);
         
         $("#result-calculate").html(temp(calculate));
         $("#result-filter").replaceWith(filterTemp(filter));
         initSelectPicker();
         financeTop = initChartFinance('finance-top','Doanh thu C8','Doanh thu C11', labels, dataTop);
         financeBottom = initChartFinance('finance-bottom','Doanh thu C11','Doanh thu C13', labels, dataBottom);
         
      }).catch(err => {
          console.log(err);
      });
    });
    
    
    $(document).on('change','.selectpicker, .partner-date', function() {
        
        let base = getLocalStorage('finance');
        if(!base){
            toastr.warning('Dữ liệu chưa cập nhật!');
            return false;
        }
        let searchData = getSearchParams('finance-form',JSON.stringify(base),'GetFinance');
       
        getSearch(searchData).then(res => {
            let {base,  filter,labels, calculate, dataSet} = res;
            const {C8, C11, C13} = dataSet;
            
            labels = Object.values(labels); 
            console.log(calculate);
            switch ('$route') {
              case "finance":
                  
                  financeTop.data.datasets[0].data = C8;
                  financeTop.data.datasets[1].data = C11;
                  financeTop.data.labels = Object.values(labels);
                  financeTop.update();
                  
                  financeBottom.data.datasets[0].data = C11;
                  financeBottom.data.datasets[1].data = C13;
                  financeBottom.data.labels = Object.values(labels);
                  financeBottom.update();
                  
                  $("#result-calculate").html(temp(calculate));
                  
                  break;
              default:
                  break;
            }
        });
    });
    
    
JS;
$this->registerJs($js);