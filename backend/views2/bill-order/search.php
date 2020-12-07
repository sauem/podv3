<?php
?>
<div class="row">
    <div class="col-md-3">
        <select title="Sản phẩm"
                data-actions-box="true"
                data-live-search="true"
                name="" class="selectpicker mr-3"
                multiple data-selected-text-format="count"
                data-style="btn-light">
            <?php if (!empty($filterProducts)) { ?>
                <?php foreach ($filterProducts as $product) {
                    ?>
                    <option value="<?= $product?>"><?= $product?></option>
                    <?php
                } ?>
            <?php } ?>
        </select>
    </div>
    <div class="col-md-3">
        <select title="Phương thức thanh toán"
                data-actions-box="true"
                data-live-search="true"
                name="" class="selectpicker mr-3"
                multiple data-selected-text-format="count"
                data-style="btn-light">
            <?php if (!empty($filterPayments)) { ?>
                <?php foreach ($filterPayments as $payment) {
                    ?>
                    <option value="<?= $payment?>"><?= $payment?></option>
                    <?php
                } ?>
            <?php } ?>
        </select>
    </div>
    <div class="col-md-3">
        <select title="Quốc gia"
                data-actions-box="true"
                data-live-search="true"
                name="" class="selectpicker mr-3"
                multiple data-selected-text-format="count"
                data-style="btn-light">
            <?php if (!empty($filterCountries)) { ?>
                <?php foreach ($filterCountries as $code => $country) {
                    ?>
                    <option value="<?= $code?>"><?= $country?></option>
                    <?php
                } ?>
            <?php } ?>
        </select>
    </div>
</div>
