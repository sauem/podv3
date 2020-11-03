<?php

?>
    <div class="card">
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
            <p>Doanh thu có ship đã chuyển thành công</p>
            <h3>{{totalAmountSuccess}}</h3>
        </div>
        <div class="col text-center">
            <p>Doanh thu có ship (C8)</p>
            <h3>{{money totalAmount}}</h3>
        </div>
        <div class="col text-center">
            <p>Tổng phí vận chuyển</p>
            <h3>{{money totalShip}}</h3>
        </div>
        <div class="col-2 text-center">
            <p>C3 (Contacts - Duplicate)</p>
            <h3>{{totalC3}}</h3>
        </div>
        <div class="col text-center">
            <p>C8 (Ok)</p>
            <h3>{{totalC8}}</h3>
        </div>
        <div class="col text-center">
            <p>C8/C3</p>
            <h3>{{totalC8C3}}%</h3>
        </div>
    </script>
<?php
$js = <<<JS
    $(document).ready(function() {
        let result = null;
        
        getSale('$partner').then(res => {
            if(!res){
                result = "Dữ liệu trống!";
            }
           
            initChartIndex(res.labels, res.dataSet);
        });
    });


JS;
$this->registerJs($js);