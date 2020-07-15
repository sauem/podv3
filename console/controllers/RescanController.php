<?php

namespace console\controllers;
use backend\jobs\doScanContact;

class RescanController extends \yii\console\Controller
{
    public function actionIndex(){
      echo  doScanContact::apply();
      return 0;
    }
}