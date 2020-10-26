<?php

use yii\helpers\Url;

?>
<script>
    let AJAX_ENDPOINT = {
        // load lead info
        leadContactInfo: '<?= Url::toRoute(['/ajax-order/lead-contact'])?>',
        loadOrder: '<?= Url::toRoute(['/ajax-order/load-order'])?>',
        checkOrderCode: '<?= Url::toRoute(['/ajax-order/check-order-code'])?>',
    }
</script>