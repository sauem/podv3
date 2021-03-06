<?php


namespace backend\controllers;


use backend\models\AnalyticsSearch;
use backend\models\ContactsModel;
use common\helper\Helper;

class AjaxAnalyticsController extends BaseController
{
    public function init()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function actionIndex()
    {
        $params  = \Yii::$app->request->queryParams;

        $query = new AnalyticsSearch();

        $query = $query->search($params);

        return [
          'success' => 1,
          'data' => $query
        ];
    }
}