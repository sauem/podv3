<?php
?>

    <div class="col-12 text-center">
        <h4 class="card-title text-danger">Bộ lọc</h4>
    </div>
    <div class="col">
        <select name="marketer" class="selectpicker" multiple data-selected-text-format="count" data-style="btn-light">
            <?php
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
        <select name="source" class="selectpicker" multiple data-selected-text-format="count" data-style="btn-light">
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
        <select name="product" class="selectpicker" multiple data-selected-text-format="count" data-style="btn-light">
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
        <select name="sale" class="selectpicker" multiple data-selected-text-format="count" data-style="btn-light">
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
        <select class="form-control">
            <option>Ngày</option>
        </select>
    </div>
<?php
$js = <<<JS
    $(".selectpicker").change(function() {
        let val = $(this).val();    
        
    });
JS;
$this->registerJs($js);
