<?php
?>
<div class="card-box widget-inline">
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="p-2 text-center">
                <h3><span data-plugin="counterup">0</span></h3>
                <p class="text-muted font-15 mb-0"> Chưa chuyển hàng</p>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="p-2 text-center">
                <h3><span data-plugin="counterup">0</span></h3>
                <p class="text-muted font-15 mb-0">Chờ chuyển hàng</p>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="p-2 text-center">
                <h3><span data-plugin="counterup">0</span></h3>
                <p class="text-muted font-15 mb-0">Đang chuyển hàng</p>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="p-2 text-center">
                <h3><span data-plugin="counterup">0</span></h3>
                <p class="text-muted font-15 mb-0">Đã chuyển hàng</p>
            </div>
        </div>

    </div>

</div>
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs nav-bordered">
            <li class="nav-item">
                <a href="#tab-b1" data-toggle="tab" aria-expanded="false" class="nav-link active">
                    Chưa chuyển hàng
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-b2" data-toggle="tab" aria-expanded="true" class="nav-link">
                    Chờ chuyển hàng
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-b3" data-toggle="tab" aria-expanded="true" class="nav-link">
                    Đang chuyển hàng
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-b4" data-toggle="tab" aria-expanded="true" class="nav-link">
                    Đã chuyển hàng
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="text-warning nav-link">
                    <i class="fe-save"></i> Lưu bộ lọc
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <?= $this->render('search', [
            'filterProducts' => $filterProducts,
            'filterPayments' => $filterPayments,
            'filterCountries' => $filterCountries,
        ]) ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab-b1">
                <?= $this->render('tab-order', ['orders' => $orders]) ?>
            </div>
            <div class="tab-pane" id="tab-b2">
                <h1>HELLL2</h1>
            </div>
            <div class="tab-pane" id="tab-b3">
                <h1>HELLL3</h1>
            </div>
            <div class="tab-pane" id="tab-b4">
                <h1>HELLL4</h1>
            </div>
        </div>
    </div>

</div>
