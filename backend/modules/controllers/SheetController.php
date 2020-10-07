<?php


namespace backend\modules\controllers;


use yii\web\Controller;
use yii\web\Response;

class SheetController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (\Yii::$app->request->isPost) {
            return \Yii::$app->request->post();
        }
        return "HOHIH";
    }
}