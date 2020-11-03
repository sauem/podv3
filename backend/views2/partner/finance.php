<?php
?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Báo cáo tài chính</h4>
    </div>
    <div class="card-body">
        <h4 class="text-center mt-4">BẢNG TỔNG HỢP C8 C11</h4>
        <canvas id="fiance-chart1" height="350">

        </canvas>

        <h4 class="text-center mt-4">BẢNG TỔNG HỢP CONTACT</h4>
        <canvas id="fiance-chart2" height="350">

        </canvas>
    </div>
</div>

<?php

$js =<<<JS

JS;
$this->registerJs($js);