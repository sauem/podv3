<?php
namespace backend\jobs;

use backend\jobs\doScanContact;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class scanNewContact extends BaseObject implements JobInterface
{
    public function execute($queue)
    {
        doScanContact::apply();
    }
}