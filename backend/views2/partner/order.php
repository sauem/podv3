<?php
?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Báo cáo đơn hàng</h4>
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
<?php
$js = <<<JS
    $(document).ready(function() {
        let html = $("#order-template").html();
        let template = Handlebars.compile(html);
        let result = null;
        let data  = [];
        getOrderDetail("$partner").then(res => {
            if(!res){
                result = "Dữ liệu trống!";
            }
            res.map((item, key) => {
               data.push( {
                   code : item[0],
                   date_register  : item[2],
                   name : item[3],
                   phone : item[4],
                   status : item[39],
                   revenue : item[40],
                   status_shipping : item[43],
               });
            });
            result = template(data);
            $("#order-result").html(result);
            initDataTable('#result');
        });
    });
JS;
$this->registerJs($js);