<?php


namespace backend\controllers;


use backend\models\LandingPages;
use backend\models\OrdersModel;
use common\helper\Helper;
use yii\data\ActiveDataProvider;

class ClientController extends BaseController
{
    function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => LandingPages::find()->join('INNER JOIN', 'customer_pages', 'customer_pages.page_id=landing_pages.id')
                ->where(['customer_pages.user_id' => \Yii::$app->user->getId()])
        ]);
        return $this->render("index", [
            'dataProvider' => $dataProvider
        ]);
    }

    function actionOrder()
    {
        $query = OrdersModel::find()->join("INNER JOIN", "contacts", "orders.code=contacts.code")
            ->join("INNER JOIN", "landing_pages", "landing_pages.link=contacts.short_link")
            ->join("INNER JOIN", "customer_pages", "customer_pages.page_id=landing_pages.id")
            ->where(['customer_pages.user_id' => \Yii::$app->user->getId()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $this->render("order", [
            'dataProvider' => $dataProvider
        ]);
    }

}