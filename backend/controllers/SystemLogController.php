<?php


namespace backend\controllers;

use backend\models\ContactsModel;
use backend\models\LogsImport;
use backend\models\OrdersItems;
use cakebake\actionlog\model\ActionLog;
use cakebake\actionlog\model\ActionLogSearch;
use common\helper\Helper;
use yii\data\ActiveDataProvider;

class SystemLogController extends BaseController
{
    function actionIndex()
    {
        $model = new ActionLogSearch;
        $dataProvider = $model->search(\Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['id' => SORT_DESC]);

        return $this->render("index", [
            'searchModel' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
    function actionImport(){

        $dataProvider = new ActiveDataProvider([
            'query' => LogsImport::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 15
            ]
        ]);
        return $this->render("import", [
            'dataProvider' => $dataProvider
        ]);
    }

    function actionDemo()
    {
        $data = [
            'phone' => "0349991834",
            'name' => "Nguyễn đình thắng",
            'address' => 'Hà Nội',
            'zipcode' => 100000,
            'ip' => '123.24.177.254',
            'type' => 'capture_form',
            'option' => 'Lựa chọn 3',
            'register_time' => '7/30/2020 1:09:33 PM',
            'link' => 'https://ladi.huynguyen.info',
        ];

        $query = OrdersItems::find()
            ->with(['product' => function ($query) {
                $query->select(['sku', 'name']);
            }])
            ->groupBy('product_sku')
            ->addSelect(['product_sku', 'sum(price) as total'])
            ->orderBy(['total' => SORT_DESC]);
        Helper::prinf($query->all());
    }

}