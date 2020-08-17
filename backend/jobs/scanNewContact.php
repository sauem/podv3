<?php
namespace backend\jobs;

use yii\base\BaseObject;
use yii\queue\JobInterface;

class scanNewContact extends BaseObject implements JobInterface
{
    public function execute($queue)
    {
        doScanContactByCountry::apply();
    }
}