<?php


namespace backend\jobs;


use backend\models\OrdersBilling;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class removeImageDraft extends BaseObject implements JobInterface
{

    public function execute($queue)
    {
        $model = OrdersBilling::find()->where(['status' =>'draft'])
        ->andWhere(["is",'order_id' ,""])
            ->all();
        if($model){

        }
    }
}