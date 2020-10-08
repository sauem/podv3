<?php


?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <?= $this->render('_search', [
                'marketer' => $marketer,
                'source' => $source,
                'product' => $product,
                'sale' => $sale
            ]) ?>

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
                <p>RESULT INDEX</p>
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
    <div class="col text-center">
        <p>Doanh thu có ship đã chuyển thành công</p>
        <h4>0</h4>
    </div>
    <div class="col text-center">
        <p>Doanh thu có ship (C8)</p>
        <h4>0</h4>
    </div>
    <div class="col text-center">
        <p>Tổng phí vận chuyển</p>
        <h4>0</h4>
    </div>
    <div class="col text-center">
        <p>C3 (Contacts - Duplicate)</p>
        <h4>0</h4>
    </div>
    <div class="col text-center">
        <p>C8 (Ok)</p>
        <h4>0</h4>
    </div>
    <div class="col text-center">
        <p>C8/C3</p>
        <h4>0</h4>
    </div>
</script>