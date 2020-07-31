<?php

namespace backend\jobs;

use backend\models\OrdersBilling;
use common\helper\Helper;

class doScanBilling
{
    public static function scan()
    {
        $condition = [
            'AND', ['=', 'order_id', ''],
            ['active' => 'draft']
        ];
        $model = OrdersBilling::deleteAll($condition);
        if ($model) {
            return "Xóa thàh công!";
        } else {
            return $model;
        }
    }
}