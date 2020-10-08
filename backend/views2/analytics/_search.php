<?php
?>

    <div class="col-12 text-center">
        <h4 class="card-title text-danger">Bộ lọc</h4>
    </div>
    <div class="col">
        <select
                title="Marketer"
                data-actions-box="true"
                data-live-search="true"
                name="marketer"
                class="selectpicker"
                multiple data-selected-text-format="count"
                data-style="btn-light">
            <?php

            use kartik\daterange\DateRangePicker;

            if ($marketer) {
                foreach ($marketer as $name) {
                    if (!$name) {
                        $name = 'null';
                    }
                    echo "<option>{$name}</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col">
        <select title="Contact type"
                data-actions-box="true"
                data-live-search="true"
                name="source" class="selectpicker"
                multiple data-selected-text-format="count"
                data-style="btn-light">
            <?php
            if ($source) {
                foreach ($source as $type) {
                    if (!$type) {
                        $type = 'null';
                    }
                    echo "<option>{$type}</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col">
        <select title="Product"
                data-actions-box="true"
                data-live-search="true"
                name="product" class="selectpicker"
                multiple data-selected-text-format="count"
                data-style="btn-light">
            <?php
            if ($product) {
                foreach ($product as $sku => $name) {
                    if (!$sku) {
                        $name = 'null';
                    }
                    echo "<option value='$sku'>{$name}</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col">
        <select title="Sale"
                data-actions-box="true"
                data-live-search="true"
                name="sale" class="selectpicker"
                multiple data-selected-text-format="count"
                data-style="btn-light">
            <?php
            if ($sale) {
                foreach ($sale as $id => $name) {
                    if (!$sku) {
                        $name = 'null';
                    }
                    echo "<option value='$id'>{$name}</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col">
        <?php
        echo DateRangePicker::widget([
            'name' => 'date',
            'presetDropdown' => true,
            'convertFormat' => true,
            'includeMonthsFilter' => true,
            'pluginOptions' => ['locale' => ['format' => 'm/d/Y']],
            'options' => ['id' => 'report-date','placeholder' => 'Chọn ngày tạo đơn']
        ]);
        ?>
    </div>
<?php
