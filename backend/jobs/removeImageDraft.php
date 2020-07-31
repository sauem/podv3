<?php


namespace backend\jobs;


use backend\models\OrdersBilling;
use common\helper\Helper;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class removeImageDraft extends BaseObject implements JobInterface
{

    public function execute($queue)
    {
        $condition = [
            'AND',['<>','order_id',''],
            ['active' => 'draft']
        ];
        $model = OrdersBilling::deleteAll($condition);
        if($model){
            echo "Xóa thàh công!";
        }else{
            echo Helper::firstError($model);
        }
    }
}