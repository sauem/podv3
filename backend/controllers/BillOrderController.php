<?php


namespace backend\controllers;


use backend\models\ContactsModel;
use backend\models\OrdersModel;
use backend\models\Payment;
use backend\models\ProductsModel;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class BillOrderController extends BaseController
{
    public function actionIndex()
    {
        $orders = new ActiveDataProvider([
            'query' => OrdersModel::find()
        ]);

        $filterProducts = ProductsModel::select('sku','sku');
        $filterPayments  = Payment::find()->asArray()->all();
        $filterPayments = ArrayHelper::map($filterPayments,'name','name');
        $filterCountries = ArrayHelper::map(\Yii::$app->params['country'],'code','name');
        return $this->render('index',[
            'orders' => $orders,
            'filterPayments' => $filterPayments,
            'filterProducts' => $filterProducts,
            'filterCountries' => $filterCountries,
        ]);
    }
}