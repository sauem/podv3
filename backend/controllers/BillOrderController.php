<?php


namespace backend\controllers;


use backend\models\ContactsModel;
use backend\models\OrdersModel;
use yii\data\ActiveDataProvider;

class BillOrderController extends BaseController
{
    public function actionIndex()
    {
        $orders = new ActiveDataProvider([
            'query' => OrdersModel::find()
        ]);
        return $this->render('index',[
            'orders' => $orders
        ]);
    }
}