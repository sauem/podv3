<?php
?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Báo cáo tài chính</h4>
        </div>
        <div class="card-body">
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

                <div id="result-calculate"></div>
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
        </div>
        <div class="col-11 text-center">
            <div class="row">
                <div class="col">
                    <p>Sô tiền chuyển đối tác (C13)</p>
                    <h4>฿{{_C13}}</h4>
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
                    <p>Tiền đã đối soát</p>
                    <h4>฿{{money tien_da_dx}}</h4>
                </div>
                <div class="col">
                    <p>Doanh thu C13</p>
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
                    <p>Tiền chưa đối soát</p>
                    <h4>฿{{money tien_chua_dx}}</h4>
                </div>
                <div class="col">
                    <p>Doanh thu C13 chưa đối soát</p>
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
                    <p>Phí VCH chưa đối soát</p>
                    <h4>฿{{money vch_chua_dx}}</h4>
                </div>
            </div>
        </div>
    </script>
<?php

$js = <<<JS
    $(document).ready(function() {
        let html = $("#finance-template").html();
        let temp = Handlebars.compile(html);
        
        getFinance('DCOT').then(res =>{
         const {calculate,  dataSet, labels} = res;
         $("#result-calculate").replaceWith(temp(calculate));
         let dataTop = {
             column_1 : dataSet.C8,
             column_2 : dataSet.C11
         }
         let dataBottom = {
             column_1 : dataSet.C11,
             column_2 : dataSet.C13
         }
         initChartFinance('finance-top','Doanh thu C8','Doanh thu C11', labels, dataTop);
         initChartFinance('finance-bottom','Doanh thu C11','Doanh thu C13', labels, dataBottom);
      }).catch(err => {
          console.log(err);
      });
    });

    function initChartFinance(ctx, label1, label2, labels, data) {
        let topCtx = document.getElementById(ctx).getContext('2d');
        let {column_1,column_2}  = data;
        column_1 = Object.values(column_1);
        column_2 = Object.values(column_2);
        labels = Object.values(labels);
        new Chart(topCtx, {
            type: 'bar',
            animation: {
                duration: 1,
                easing: 'linear'
            },
            options: {
                maintainAspectRatio: true,
                responsive: true,
                tooltips: {
                    mode: 'index',
                    axis: 'y',
                    callbacks: {
                        label: function (tooltipItem, data) {
                            let label = data.datasets[tooltipItem.datasetIndex].label;
                            let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            return ' ' + label + ': ฿' + value.formatMoney();
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 2500,
                            callback: function (value, index, values) {
                                return formatK(value);
                            }
                        }
                    }]
                }
            },
            data: {
                datasets: [
                    {
                        label: label1,
                        backgroundColor: '#90EAFF',
                        data: column_1,
                    },
                    {
                        label: label2,
                        backgroundColor: '#3c5ab1',
                        data: column_2,
                    },
                ],
                labels: labels
            },
        });
    }
JS;
$this->registerJs($js);